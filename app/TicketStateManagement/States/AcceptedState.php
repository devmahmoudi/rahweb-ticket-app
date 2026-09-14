<?php

namespace App\TicketStateManagement\States;

use App\Models\User;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;

class AcceptedState extends State implements TicketStateInterface
{

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
        $this->ticket->update(['recipient_id' => $target->id]);

        $chat = $this->ticket->chat();
        if ($chat) {
            $chat->members()->detach($actor->id);
            $chat->members()->attach($target->id);
        }

        $this->transition(TicketState::DELEGATED, "تیکت شما تایید و به  {$target->name} منتقل شده است.", $actor);
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
        $this->rejectTransition();
    }

    /**
     * @inheritDoc
     */
}
