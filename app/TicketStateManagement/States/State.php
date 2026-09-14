<?php

namespace App\TicketStateManagement\States;

use App\Enums\Chat\ChatUserConnectionStatus;
use App\Events\TicketStateChanged;
use App\Models\Ticket;
use App\Models\User;
use App\TicketStateManagement\TicketState;
use LogicException;
use Illuminate\Support\Facades\DB;

abstract class State
{
    public function __construct(
        protected Ticket $ticket
    ) {}

    protected function transition(TicketState $state, string $message, ?User $actor = null): void
    {
        $this->ticket->update(['status' => $state->value]);

        $chat = $this->ticket->chat();
        if ($chat) {
            $chat->messages()->create([
                'body' => $message,
                'user_id' => $actor?->id ?? $this->ticket->user_id,
            ]);
        }

        TicketStateChanged::dispatch($this->ticket, $state);
    }

    protected function rejectTransition(): void
    {
        $chat = $this->ticket->chat();
        if ($chat) {
            DB::table('chat_user')
                ->where('chat_id', $chat->id)
                ->update(['status' => ChatUserConnectionStatus::BLOCKED->value]);
        }

        $this->transition(TicketState::REJECTED, 'تیکت شما رد شد');
    }

    protected function unsupported(string $transition): never
    {
        throw new LogicException("The {$transition} transition is not allowed from the current ticket state.");
    }

    /**
     * @inheritDoc
     */
    public function claim(User $actor): void
    {
        $this->unsupported('claim');
    }

    /**
     * @inheritDoc
     */
    public function delegateTo(User $actor, User $target): void
    {
        $this->unsupported('delegate');
    }

    /**
     * @inheritDoc
     */
    public function publishToWebService(User $actor): void
    {
        $this->unsupported('publishToWebService');
    }

    /**
     * @inheritDoc
     */
    public function reject(): void
    {
        $this->unsupported('reject');
    }
}
