<?php

namespace App\Repositories\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Ticket;
use App\View\Components\ConfirmCloseTicketMessage;
use App\View\Components\InitialTicketMessage;
use App\View\Components\TicketClosedMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasTicketMessageMethods
{
    public function createInitialTicketMessage(Ticket $ticket):Message|false
    {
        $initialMessageBody = app()->makeWith(InitialTicketMessage::class, ['ticket' => $ticket]);

        $messageData = [
            'body' => $initialMessageBody->render()->render(),
            'user_id' => $ticket->user_id
        ];

        return $this->create($messageData);
    }

    public function createConfirmCloseTicketMessage(Ticket $ticket):Message|false
    {
        $confirmCloseTicket = app()->makeWith(ConfirmCloseTicketMessage::class, ['ticket' => $ticket]);

        $messageData = [
            'body' => $confirmCloseTicket->render()->render(),
            'user_id' => $ticket->user_id
        ];

        return $this->create($messageData);
    }

    public function createTicketClosedMessage(Ticket $ticket):Message|false
    {
        $ticketClosedMessage = app()->makeWith(TicketClosedMessage::class, ['ticket' => $ticket]);

        $messageData = [
            'body' => $ticketClosedMessage->render()->render(),
            'user_id' => $ticket->user_id
        ];

        return $this->create($messageData);
    }
}
