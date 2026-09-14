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
        $this->transition(TicketState::DELEGATED, "Ticket delegated to {$target->name}.", $actor);
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
