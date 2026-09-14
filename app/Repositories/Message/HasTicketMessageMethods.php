<?php

namespace App\Repositories\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Ticket;
use App\View\Components\InitialTicketMessage;
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

}
