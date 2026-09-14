<?php

namespace App\Observers;

use App\Events\NewTicket;
use App\Events\TicketAssigmentChanged;
use App\Models\Chat;
use App\Models\Ticket;
use App\Repositories\Message\MessageRepository;
use App\TicketStateManagement\TicketState;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        if (
            $ticket->wasChanged('recipient_id') &&
            $ticket->status === TicketState::PENDING->value &&
            $ticket->getOriginal('recipient_id') !== null &&
            $ticket->recipient_id !== null
        ) {
            $chat = Chat::withoutGlobalScopes()
                ->where('meta', Ticket::class . ",{$ticket->id}")
                ->first();

            if ($assigner = auth()->user() and $chat) {
                app()->makeWith(MessageRepository::class, ['chat' => $chat])
                    ->createTicketAssignmentMessage($ticket, $assigner);
            }

            TicketAssigmentChanged::dispatch($ticket);
            NewTicket::dispatch($ticket);
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
