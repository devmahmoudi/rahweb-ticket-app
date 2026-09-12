<?php

namespace App\Observers;

use App\Enums\Ticket\TicketStatus;
use App\Events\NewTicket;
use App\Events\TicketAssigmentChanged;
use App\Models\Ticket;

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
            $ticket->status === TicketStatus::PENDING->value &&
            $ticket->getOriginal('recipient_id') !== null &&
            $ticket->recipient_id !== null
        ) {
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
