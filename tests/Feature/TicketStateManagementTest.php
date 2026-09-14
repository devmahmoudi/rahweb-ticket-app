<?php

namespace Tests\Feature;

use App\Enums\Chat\ChatUserConnectionStatus;
use App\Events\TicketStateChanged;
use App\Jobs\SendTicketToWebservice;
use App\Livewire\Ticket\Index;
use App\Models\Chat;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Ticket\WebServiceRepository;
use App\TicketStateManagement\TicketState;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use LogicException;
use RuntimeException;
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
        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id, 'body' => "تیکت شما توسط {$actor->name} در حال رسیدگی است"]);
    }

    public function test_accepted_ticket_can_be_delegated_or_rejected(): void
    {
        Event::fake();
        $actor = User::factory()->operator()->create();
        $target = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create(['status' => TicketState::ACCEPTED->value]);
        $chat = Chat::factory()->create(['meta' => Ticket::class . ",{$ticket->id}"]);
        $chat->members()->attach($actor->id);

        $ticket->stateManagement()->delegateTo($actor, $target);

        $this->assertSame(TicketState::DELEGATED->value, $ticket->fresh()->status);
        $this->assertSame($target->id, $ticket->fresh()->recipient_id);
        $this->assertDatabaseMissing('chat_user', ['chat_id' => $chat->id, 'user_id' => $actor->id]);
        $this->assertDatabaseHas('chat_user', ['chat_id' => $chat->id, 'user_id' => $target->id]);
        Event::assertDispatched(TicketStateChanged::class);
        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id, 'body' => "تیکت شما تایید و به  {$target->name} منتقل شده است."]);
    }

    public function test_delegated_ticket_can_be_published(): void
    {
        Queue::fake();
        Event::fake();
        $actor = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create(['status' => TicketState::DELEGATED->value]);
        $chat = Chat::factory()->create(['meta' => Ticket::class . ",{$ticket->id}"]);

        $ticket->stateManagement()->publishToWebService($actor);

        $this->assertSame(TicketState::WEBSERVICE->value, $ticket->fresh()->status);
        Event::assertDispatched(TicketStateChanged::class);
        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id, 'body' => 'تیکت شما تایید و جهت تکمیل فرایند به وب سرویس ارسال شد']);
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
            'body' => 'تیکت شما رد شد',
        ]);
        Event::assertDispatched(TicketStateChanged::class);
    }

    public function test_delegated_ticket_dispatches_webservice_job_with_webservice_queue(): void
    {
        Queue::fake();
        $actor = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create(['status' => TicketState::DELEGATED->value]);

        $ticket->stateManagement()->publishToWebService($actor);

        Queue::assertPushedOn('webservice', SendTicketToWebservice::class, function ($job) use ($ticket) {
            return $job->ticket->id === $ticket->id;
        });
    }

    public function test_webservice_job_handles_successful_response_without_throwing(): void
    {
        $owner = User::factory()->customer()->create();
        $ticket = Ticket::factory()->create([
            'status' => TicketState::DELEGATED->value,
            'user_id' => $owner->id,
        ]);

        $this->app->instance(WebServiceRepository::class, new class implements WebServiceRepository
        {
            public function sendTicket(Ticket $ticket): Response
            {
                $psrResponse = new Psr7Response(200, [], json_encode([
                    'ticket_id' => $ticket->id,
                    'status' => 'succeed',
                ]));

                return new Response($psrResponse);
            }
        });

        $job = new SendTicketToWebservice($ticket);

        $job->handle(app(WebServiceRepository::class));

        $this->assertTrue(true);
    }

    public function test_webservice_job_throws_when_repository_returns_unsuccessful_response(): void
    {
        Notification::fake();
        $ticket = Ticket::factory()->create(['status' => TicketState::DELEGATED->value]);

        $this->app->instance(WebServiceRepository::class, new class implements WebServiceRepository
        {
            public function sendTicket(Ticket $ticket): Response
            {
                return new Response(new Psr7Response(500, [], json_encode([
                    'message' => 'Unavailable',
                ])));
            }
        });

        $job = new SendTicketToWebservice($ticket);

        $this->expectException(RuntimeException::class);
        $job->handle(app(WebServiceRepository::class));
    }

    public function test_webservice_retry_schedule_is_registered_hourly(): void
    {
        Artisan::call('schedule:list');

        $this->assertStringContainsString('queue:retry --queue=webservice', Artisan::output());
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

    public function test_bulk_mode_displays_selection_checkbox_for_pending_workgroup_tickets(): void
    {
        $operator = User::factory()->operator()->create();
        $ticket = Ticket::factory()->pending()->create();
        $operator->workgroups()->attach($ticket->workgroup_id);

        $this->actingAs($operator);

        Livewire::test(Index::class)
            ->set('status', TicketState::PENDING->value)
            ->set('bulkMode', true)
            ->assertSee('type="checkbox"', false)
            ->assertSee('لغو حالت گروهی');
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

    public function test_ticket_index_handles_new_ticket_broadcasts(): void
    {
        $operator = User::factory()->operator()->create();
        $this->actingAs($operator);

        $component = app(Index::class);

        $this->assertSame(
            'newTicket',
            $component->getListeners()["echo-private:user.{$operator->id},NewTicket"]
        );
        $this->assertTrue(is_callable([$component, 'newTicket']));

        $component->newTicket();
        $this->assertTrue(true);
    }

    public function test_bulk_actions_for_accepted_tickets_are_delegate_and_reject(): void
    {
        $operator = User::factory()->operator()->create();
        $tickets = Ticket::factory()->count(2)->create([
            'status' => TicketState::ACCEPTED->value,
            'recipient_id' => $operator->id,
        ]);

        $this->actingAs($operator);

        Livewire::test(Index::class)
            ->set('status', TicketState::ACCEPTED->value)
            ->set('bulkMode', true)
            ->set('selectedTicketIds', $tickets->pluck('id')->all())
            ->assertSee('ارجاع انتخاب‌شده‌ها')
            ->assertSee('رد انتخاب‌شده‌ها')
            ->assertDontSee('پذیرش انتخاب‌شده‌ها')
            ->assertDontSee('ارسال انتخاب‌شده‌ها به وب سرویس')
            ->call('applyBulkTransition', 'reject');

        $this->assertSame(
            2,
            Ticket::query()->whereIn('id', $tickets->pluck('id'))->where('status', TicketState::REJECTED->value)->count()
        );
    }

    public function test_bulk_delegation_uses_one_superadmin_for_all_selected_tickets(): void
    {
        $operator = User::factory()->operator()->create();
        $superadmin = User::factory()->superadmin()->create();
        $tickets = Ticket::factory()->count(2)->create([
            'status' => TicketState::ACCEPTED->value,
            'recipient_id' => $operator->id,
        ]);

        $this->actingAs($operator);

        Livewire::test(Index::class)
            ->set('status', TicketState::ACCEPTED->value)
            ->set('bulkMode', true)
            ->set('selectedTicketIds', $tickets->pluck('id')->all())
            ->call('openBulkDelegateModal')
            ->assertSet('showBulkDelegateModal', true)
            ->set('bulkDelegateTargetId', $superadmin->id)
            ->call('applyBulkDelegate');

        $this->assertSame(
            2,
            Ticket::withoutGlobalScopes()->whereIn('id', $tickets->pluck('id'))
                ->where('status', TicketState::DELEGATED->value)
                ->where('recipient_id', $superadmin->id)
                ->count()
        );
    }
}
