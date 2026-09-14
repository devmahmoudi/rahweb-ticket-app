<?php

namespace App\Repositories\Ticket;

use App\Models\Ticket;
use Illuminate\Http\Client\Response;

interface WebServiceRepository
{
    public function sendTicket(Ticket $ticket): Response;
}
