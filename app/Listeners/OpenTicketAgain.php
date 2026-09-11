<?php

namespace App\Listeners;

use App\Enums\Ticket\TicketStatus;
use App\Events\MessageCreated;
use App\Events\NewTicket;
use App\Repositories\Chat\ChatRepository;
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OpenTicketAgain
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private TicketRepository $ticketRepository,
        private ChatRepository $chatRepository,
        private UserRepository $userRepository,
    ){}

    /**
     * Handle the event.
     */
    public function handle(MessageCreated $event): void
    {
        if(!$ticket = $this->chatRepository->findRelevantTicket($event->message->chat_id))
            return;

        if($ticket->status != TicketStatus::CLOSED->value)
            return;

        if(!$this->ticketRepository->update($ticket, [
            'status' => TicketStatus::PENDING,
        ]))
            return;

        if(!$operator = $this->userRepository->find($ticket->recipient_id))
            return;

        $this->chatRepository->joinMember($event->message->chat, $operator);

        broadcast(new NewTicket($ticket))->toOthers();
    }
}
