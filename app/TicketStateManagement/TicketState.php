<?php

namespace App\TicketStateManagement;

enum TicketState: string
{
    case PENDING = "pending for claim";

    case ACCEPTED = "accepted for claim";

    case DELEGATED = "delegated to admin";

    case WEBSERVICE = "send to web service";

    case REJECTED = "rejected";
}
