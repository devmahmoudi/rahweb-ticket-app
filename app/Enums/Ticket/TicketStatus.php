<?php

namespace App\Enums\Ticket;

enum TicketStatus: string
{
    case PENDING = 'در حال رسیدگی';

    case CLOSED = 'بسته شده';

    case WAITING = 'در انتظار اوپراتور'; // Waiting for receive with one of users
}
