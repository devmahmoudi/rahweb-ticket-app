<?php

namespace App\Observers;

use App\Models\Chat;
use App\Models\Ticket;
use App\Repositories\Message\MessageRepository;
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

            $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $chat]);

            if (!$messageRepository->createInitialTicketMessage($ticket)) {
                return false;
            }

            return $chat;
        });
    }
}
