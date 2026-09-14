<?php

namespace App\Repositories\WebService;

use App\Models\Ticket;
use Illuminate\Http\Client\Response;

interface WebServiceRepository
{
    public function sendTicket(Ticket $ticket): Response;
}
