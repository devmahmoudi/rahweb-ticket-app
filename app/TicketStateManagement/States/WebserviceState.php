<?php

namespace App\TicketStateManagement\States;

use App\Models\User;
use App\TicketStateManagement\TicketStateInterface;

class WebserviceState extends State implements TicketStateInterface
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
