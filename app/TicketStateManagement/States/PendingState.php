<?php

namespace App\TicketStateManagement\States;

use App\Models\User;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;

class PendingState extends State implements TicketStateInterface
{

    /**
     * @inheritDoc
     */
    public function claim(User $actor): void
    {
        $this->ticket->update(['recipient_id' => $actor->id]);
        $this->transition(TicketState::ACCEPTED, 'Ticket claimed.', $actor);
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

    /**
     * @inheritDoc
     */
    public function askClose(): void
    {
        $this->unsupported('askClose');
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $this->unsupported('close');
    }
}
