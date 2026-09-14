<?php

namespace App\TicketStateManagement\States;

use App\Models\User;
use App\Repositories\Chat\ChatRepository;
use App\Repositories\TicketRepository;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;
use Illuminate\Support\Facades\DB;

class PendingState extends State implements TicketStateInterface
{
    /**
     * @inheritDoc
     */
    public function claim(User $actor): void
    {
        $ticketToClaim = $this->ticket;

        cache()->lock(config('ticket.accept-cache-lock-prefix') . $this->ticket->id, 2)->block(2, function () use ($ticketToClaim, $actor): bool {
            $ticketToClaim->refresh();

            DB::transaction(function () use ($ticketToClaim, $actor): void {
                $this->ticket->update(['recipient_id' => $actor->id]);

                if (!$chat = $this->ticket->chat()->withoutGlobalScopes()->first()) {
                    throw new \Error("Chat for ticket $ticketToClaim->id not found");
                }

                $chat->members()->attach($actor);
            });

            $this->transition(TicketState::ACCEPTED, "تیکت شما توسط {$actor->name} در حال رسیدگی است", $actor);

            return true;
        });
    }
}
