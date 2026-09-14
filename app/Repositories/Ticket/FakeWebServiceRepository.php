<?php

namespace App\Repositories\Ticket;

use App\Models\Ticket;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Client\Response;

class FakeWebServiceRepository implements WebServiceRepository
{
    public function sendTicket(Ticket $ticket): Response
    {
        $isSuccessful = random_int(0, 1) === 0;

        $statusCode = $isSuccessful ? 200 : 500;
        $status = $isSuccessful ? 'succeed' : 'internal server error';

        $psrResponse = new Psr7Response($statusCode, [], json_encode([
            'ticket_id' => $ticket->id,
            'status' => $status,
        ]));

        return new Response($psrResponse);
    }
}
