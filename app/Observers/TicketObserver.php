<?php

namespace App\Observers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Ticket;
use App\View\Components\InitialTicketMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        DB::transaction(function () use ($ticket): Chat|false {
            $chatData = [
                'name' => config('ticket.chat-name-prefix') . Str::words($ticket->title, 5),
                'meta' => Ticket::class . ",{$ticket->id}",
                'link' => config('ticket.chat-link-prefix') . $ticket->id,
            ];

            if (!$chat = $ticket->chat()->create($chatData))
                throw new \Exception("Can't create chat for ticket $ticket->id");

            $chat->members()->attach($ticket->owner);

            $chat->save();

            if (!$this->createInitialTicketMessage($ticket))
                throw new \Exception("Create initial message for ticket $ticket->id failed");

            return $chat;
        });
    }

    public function createInitialTicketMessage(Ticket $ticket):Message|false
    {
        $initialMessageBody = app()->makeWith(InitialTicketMessage::class, ['ticket' => $ticket]);

        $messageData = [
            'body' => $initialMessageBody->render()->render(),
            'user_id' => $ticket->user_id
        ];

        return $ticket->chat->messages()->create($messageData);
    }
}
