<?php

namespace Tests\Feature;

use App\Enums\Chat\ChatUserConnectionStatus;
use App\Events\TicketStateChanged;
use App\Livewire\Ticket\Index;
use App\Models\Chat;
use App\Models\Ticket;
use App\Models\User;
use App\TicketStateManagement\TicketState;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use LogicException;
use Tests\TestCase;

class TicketStateManagementTest extends TestCase
{
    public function test_pending_ticket_can_be_claimed(): void
    {
        Event::fake();
        $actor = User::factory()->operator()->create();
        $ticket = Ticket::factory()->pending()->create();
        $chat = Chat::factory()->create(['meta' => Ticket::class . ",{$ticket->id}"]);

        $ticket->stateManagement()->claim($actor);

        $this->assertSame(TicketState::ACCEPTED->value, $ticket->fresh()->status);
        $this->assertSame($actor->id, $ticket->fresh()->recipient_id);
        Event::assertDispatched(TicketStateChanged::class);
        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id, 'body' => 'Ticket claimed.']);
    }

    public function test_accepted_ticket_can_be_delegated_or_rejected(): void
    {
        Event::fake();
        $actor = User::factory()->operator()->create();
        $target = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create(['status' => TicketState::ACCEPTED->value]);
        $chat = Chat::factory()->create(['meta' => Ticket::class . ",{$ticket->id}"]);

        $ticket->stateManagement()->delegateTo($actor, $target);

        $this->assertSame(TicketState::DELEGATED->value, $ticket->fresh()->status);
        $this->assertSame($target->id, $ticket->fresh()->recipient_id);
        Event::assertDispatched(TicketStateChanged::class);
        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id, 'body' => "Ticket delegated to {$target->name}."]);
    }

    public function test_delegated_ticket_can_be_published(): void
    {
        Event::fake();
        $actor = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create(['status' => TicketState::DELEGATED->value]);
        $chat = Chat::factory()->create(['meta' => Ticket::class . ",{$ticket->id}"]);

        $ticket->stateManagement()->publishToWebService($actor);

        $this->assertSame(TicketState::WEBSERVICE->value, $ticket->fresh()->status);
        Event::assertDispatched(TicketStateChanged::class);
        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id, 'body' => 'Ticket published to web service.']);
    }

    public function test_rejecting_an_accepted_ticket_blocks_chat_members_and_creates_alert(): void
    {
        Event::fake();
        $ticket = Ticket::factory()->create(['status' => TicketState::ACCEPTED->value]);
        $member = User::factory()->operator()->create();
        $chat = Chat::factory()->create(['meta' => Ticket::class . ",{$ticket->id}"]);
        $chat->members()->attach([$ticket->user_id, $member->id]);

        $ticket->stateManagement()->reject();

        $this->assertSame(TicketState::REJECTED->value, $ticket->fresh()->status);
        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $chat->id,
            'user_id' => $member->id,
            'status' => ChatUserConnectionStatus::BLOCKED->value,
        ]);
        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'body' => 'Ticket rejected.',
        ]);
        Event::assertDispatched(TicketStateChanged::class);
    }

    public function test_each_state_rejects_unsupported_transitions(): void
    {
        $actor = User::factory()->operator()->create();
        $target = User::factory()->operator()->create();

        $cases = [
            [TicketState::PENDING, 'delegateTo', [$actor, $target]],
            [TicketState::ACCEPTED, 'claim', [$actor]],
            [TicketState::DELEGATED, 'delegateTo', [$actor, $target]],
            [TicketState::WEBSERVICE, 'publishToWebService', [$actor]],
            [TicketState::REJECTED, 'claim', [$actor]],
        ];

        foreach ($cases as [$state, $method, $arguments]) {
            $ticket = Ticket::factory()->create(['status' => $state->value]);

            $this->assertThrows(fn () => $ticket->stateManagement()->{$method}(...$arguments), LogicException::class);
        }
    }

    public function test_ticket_index_displays_only_the_current_state_transitions_with_confirmation(): void
    {
        $operator = User::factory()->operator()->create();
        $ticket = Ticket::factory()->pending()->operator($operator)->create();

        $this->actingAs($operator);

        Livewire::test(Index::class)
            ->set('status', TicketState::PENDING->value)
            ->assertSee('پذیرش')
            ->assertSee('wire:confirm')
            ->assertDontSee('ارسال به وب سرویس');
    }

    public function test_ticket_index_can_delegate_an_accepted_ticket_to_a_superadmin(): void
    {
        $operator = User::factory()->operator()->create();
        $superadmin = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create([
            'status' => TicketState::ACCEPTED->value,
            'recipient_id' => $operator->id,
        ]);

        $this->actingAs($operator);

        Livewire::test(Index::class)
            ->set('status', TicketState::ACCEPTED->value)
            ->set('delegateTargetId', $superadmin->id)
            ->call('delegate', $ticket->id);

        $this->assertSame(TicketState::DELEGATED->value, $ticket->fresh()->status);
        $this->assertSame($superadmin->id, $ticket->fresh()->recipient_id);
    }
}
