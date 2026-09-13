<?php

namespace Tests\Feature;

use App\Enums\Ticket\TicketStatus;
use App\Events\NewTicket;
use App\Events\TicketAssigmentChanged;
use App\Livewire\Cartable\Tickets as CartableTickets;
use App\Livewire\Ticket\Index;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Tests\TestCase;

class TicketAssignmentTest extends TestCase
{
    public function test_only_the_current_recipient_and_admin_can_assign_open_tickets(): void
    {
        $recipient = User::factory()->operator()->create();
        $otherOperator = User::factory()->operator()->create();
        $admin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->pending()->operator($recipient)->create();

        $this->assertTrue($recipient->can('assign', $ticket));
        $this->assertTrue($admin->can('assign', $ticket));
        $this->assertFalse($otherOperator->can('assign', $ticket));

        $ticket->update(['status' => TicketStatus::CLOSED->value]);

        $this->assertFalse($recipient->can('assign', $ticket));
        $this->assertFalse($admin->can('assign', $ticket));
    }

    public function test_assignment_target_must_be_another_operator_or_admin(): void
    {
        $recipient = User::factory()->operator()->create();
        $ticket = Ticket::factory()->pending()->operator($recipient)->create();
        $customer = User::factory()->customer()->create();
        $newOperator = User::factory()->operator()->create();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $component = app(Index::class);
        $component->mount();
        $component->assignmentTicketId = $ticket->id;
        $component->assignmentUserId = $recipient->id;
        $component->assignTicket();

        $this->assertSame($recipient->id, $ticket->fresh()->recipient_id);

        $component->assignmentUserId = $customer->id;
        try {
            $component->assignTicket();
            $this->fail('A customer cannot be an assignment target.');
        } catch (ModelNotFoundException) {
            $this->assertTrue(true);
        }

        $this->assertSame($recipient->id, $ticket->fresh()->recipient_id);

        $component->assignmentUserId = $newOperator->id;
        $component->assignTicket();

        $this->assertSame($newOperator->id, $ticket->fresh()->recipient_id);
    }

    public function test_assignment_dispatches_assignment_and_targeted_new_ticket_events(): void
    {
        Event::fakeExcept(['eloquent.updated: ' . Ticket::class]);

        $recipient = User::factory()->operator()->create();
        $target = User::factory()->admin()->create();
        $ticket = Ticket::factory()->pending()->operator($recipient)->create();

        $ticket->update(['recipient_id' => $target->id]);

        Event::assertDispatched(TicketAssigmentChanged::class, function ($event) use ($ticket, $target) {
            return $event->ticket->is($ticket) && $event->ticket->recipient_id === $target->id;
        });
        Event::assertDispatched(NewTicket::class, function ($event) use ($ticket, $target) {
            return $event->ticket->is($ticket) && $event->ticket->recipient_id === $target->id;
        });
    }

    public function test_assignment_creates_an_alert_message_from_the_assigner_in_the_ticket_chat(): void
    {
        Event::fakeExcept(['eloquent.updated: ' . Ticket::class]);

        $assigner = User::factory()->admin()->create();
        $recipient = User::factory()->operator()->create();
        $target = User::factory()->operator()->create();
        $ticket = Ticket::factory()->pending()->operator($recipient)->create();

        $chat = Chat::factory()->create([
            'meta' => Ticket::class . ",{$ticket->id}",
        ]);
        $chat->members()->attach($assigner);

        $this->actingAs($assigner);
        $ticket->update(['recipient_id' => $target->id]);

        $message = $ticket->chat()->messages()->latest('id')->first();

        $this->assertNotNull($message);
        $this->assertSame($assigner->id, $message->user_id);
        $this->assertSame($ticket->chat()->id, $message->chat_id);
        $this->assertSame(
            "{$assigner->name} تیکت شما را به  {$target->name} ارجاع داد",
            $message->body
        );
    }

    public function test_new_ticket_uses_workgroup_without_recipient_and_user_channel_with_recipient(): void
    {
        $ticket = Ticket::factory()->waiting()->create(['recipient_id' => null]);
        $workgroupChannel = (new NewTicket($ticket))->broadcastOn()[0];

        $this->assertSame("private-workgroup.{$ticket->workgroup_id}", $workgroupChannel->name);

        $target = User::factory()->admin()->create();
        $ticket->update(['recipient_id' => $target->id, 'status' => TicketStatus::PENDING->value]);
        $userChannel = (new NewTicket($ticket))->broadcastOn()[0];

        $this->assertSame("private-user.{$target->id}", $userChannel->name);
    }

    public function test_both_ticket_lists_render_assignment_button_only_when_authorized(): void
    {
        $admin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->pending()->operator($admin)->create();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('status', TicketStatus::PENDING->value)
            ->assertSee('واگذاری');

        Livewire::test(CartableTickets::class)
            ->assertSee('واگذاری');

        $customer = User::factory()->customer()->create();
        $customerTicket = Ticket::factory()->pending()->create(['user_id' => $customer->id]);

        $this->actingAs($customer);

        Livewire::test(Index::class)
            ->set('status', TicketStatus::PENDING->value)
            ->assertDontSee('واگذاری');

        Livewire::test(CartableTickets::class)
            ->assertDontSee('واگذاری');
    }

    public function test_cartable_listens_on_the_authenticated_user_channel_and_refreshes(): void
    {
        $target = User::factory()->admin()->create();
        $ticket = Ticket::factory()->pending()->operator($target)->create();

        $this->actingAs($target);

        $this->assertArrayHasKey(
            "echo-private:user.{$target->id},NewTicket",
            app(CartableTickets::class)->getListeners()
        );

        $component = app(CartableTickets::class);
        $component->mount(app(\App\Repositories\TicketRepository::class));
        $component->newTicket(['ticket' => ['id' => $ticket->id]]);

        $this->assertTrue($component->tickets->contains('id', $ticket->id));
    }
}
