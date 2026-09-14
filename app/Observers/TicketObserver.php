<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Repositories\Chat\ChatRepository;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $chatRepository = app()->make(ChatRepository::class);

        if(!$chatRepository->createForTicket($ticket))
            throw new \Exception("Create chat for new ticket failed");
    }
}
