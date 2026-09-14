<?php

namespace App\TicketStateManagement\States;

use App\Jobs\SendTicketToWebservice;
use App\Models\User;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;

class DelegatedState extends State implements TicketStateInterface
{
    /**
     * @inheritDoc
     */
    public function publishToWebService(User $actor): void
    {
        SendTicketToWebservice::dispatch($this->ticket);

        $this->transition(TicketState::WEBSERVICE, "تیکت شما تایید و جهت تکمیل فرایند به وب سرویس ارسال شد", $actor);
    }

    /**
     * @inheritDoc
     */
    public function reject(): void
    {
        $this->rejectTransition();
    }

}
