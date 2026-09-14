<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Chat\ChatRepository;
use App\TicketStateManagement\TicketState;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TicketRepository
{
    public function count(array $where = [], ?string $TicketState = null): int
    {
        return
            Ticket::where($where)
            ->when($TicketState, function ($query) use ($TicketState){
                $query->where('status', $TicketState);
            })->count();
    }

    /**
     * Returns pending status tickets
     *
     * @param bool $pagination
     * @param int|null $perpage
     * @return Collection
     */
    public function pendingTickets(bool $pagination = true, ?int $perpage = 10):mixed
    {
        $query = Ticket::where('status', TicketState::PENDING->value);

        return $pagination ?
            $query->paginate($perpage) :
            $query->get();
    }

    public function cartableTickets(bool $pagination = true, ?int $perpage = 10):mixed
    {
        $query = Ticket::where('status', TicketState::PENDING->value)->orWhere('recipient_id', auth()->id());


        return $pagination ?
            $query->paginate($perpage) :
            $query->get();
    }

    public function getWithStatusScope(string $status, bool $pagination = true, ?int $perpage = 10):mixed
    {
        $query = Ticket::where('status', $status);

        return $pagination ?
            $query->paginate($perpage) :
            $query->get();
    }

    /**
     * Create new ticket with relevant chat and its initial message
     *
     * @param array $data
     * @return Ticket|false
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create(array $data):Ticket|false
    {
        DB::beginTransaction();

        if(!$ticket = Ticket::create($data))
            return false;

        $chatRepository = app()->make(ChatRepository::class);

        if(!$chat = $chatRepository->createForTicket($ticket))
            return false;

        DB::commit();

        return $ticket;
    }

    /**
     * Accept ticket for handling and answer to customer often it does with operator
     *
     * @param Ticket $ticket
     * @param User|null $acceptable the user/operator who accept ticket, current user id will set if it is null
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function accept(Ticket $ticket, ?User $acceptable = null):bool
    {
        return cache()->lock(config('ticket.accept-cache-lock-prefix') . $ticket->id, 2)->block(2, function () use ($ticket, $acceptable): bool {
            $ticket->refresh();

            if ($ticket->status !== TicketState::PENDING->value) {
                return false;
            }

            DB::beginTransaction();

            $recipient = $acceptable ?? auth()->user();

            $ticket->stateManagement()->claim($recipient);

            if (!$chat = $this->findRelevantChat($ticket)) {
                DB::rollBack();
                return false;
            }

            app(ChatRepository::class)->joinMember($chat, $recipient);

            DB::commit();

            return true;
        });
    }

    public function findRelevantChat(Ticket $ticket):Chat|null
    {
        return Chat::withoutGlobalScopes()->where('meta', Ticket::class . ",$ticket->id")->first();
    }

    public function update(Ticket $ticket, array $data):bool
    {
        return $ticket->update($data);
    }

    public function delete(Ticket $ticket):bool
    {
        return (bool)$ticket->delete();
    }

    public function paginate(int $perPage = 20)
    {
        return Ticket::paginate($perPage);
    }

    public function all(array $columns = ['*']):Collection
    {
        return Ticket::all($columns);
    }
}
