<?php

namespace App\Repositories\Chat;

use App\Models\Chat;
use App\Models\Ticket;
use App\Repositories\Message\MessageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasTicketChatMethods
{
    public function createForTicket(Ticket $ticket):Chat|false
    {
        DB::beginTransaction();

        $chatData = [
            'name' => config('ticket.chat-name-prefix') . Str::words($ticket->title, 5),
            'meta' => Ticket::class . ",{$ticket->id}",
            'link' => config('ticket.chat-link-prefix') . $ticket->id,
        ];

        if(!$chat = $this->create($chatData, [auth()->id()]))
            return false;

        $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $chat]);

        if(!$messageRepository->createInitialTicketMessage($ticket))
            return false;

        DB::commit();

        return $chat;
    }

    public function findRelevantTicket(Chat|int $chat):Ticket|null
    {
        if(is_int($chat))
            if(!$chat = $this->find($chat))
                return null;

        if(!$id = Str::of($chat->meta)->explode(',')[1])
            return null;

        return Ticket::find($id) ?? null;

    }
}
