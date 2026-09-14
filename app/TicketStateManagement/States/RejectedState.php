<?php

namespace App\TicketStateManagement\States;

use App\TicketStateManagement\TicketStateInterface;

class RejectedState extends State implements TicketStateInterface
{
    // ticket in reject state has not any transition
}
