<?php

namespace App\TicketStateManagement\States;

use App\Models\User;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;

class DelegatedState extends State implements TicketStateInterface
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
        $this->transition(TicketState::WEBSERVICE, "تیکت شما تایید و جهت تکمیل فرایند به وب سرویس ارسال شد", $actor);
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
