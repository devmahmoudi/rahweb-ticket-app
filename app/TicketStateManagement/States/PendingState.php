<?php

namespace App\TicketStateManagement\States;

use App\Models\User;
use App\Repositories\TicketRepository;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;

class PendingState extends State implements TicketStateInterface
{
    /**
     * @inheritDoc
     */
    public function claim(User $actor): void
    {
        $this->ticket->update(['recipient_id' => $actor->id]);

        $ticketRepository = app(TicketRepository::class);
        $chat = $ticketRepository->findRelevantChat(($this->ticket));
        $chat->members()->attach($actor);

        $this->transition(TicketState::ACCEPTED, "تیکت شما توسط {$actor->name} در حال رسیدگی است", $actor);
    }
}
