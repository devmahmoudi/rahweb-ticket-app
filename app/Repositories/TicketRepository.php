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
    public function findRelevantChat(Ticket $ticket):Chat|null
    {
        return Chat::withoutGlobalScopes()
            ->where('chatable_type', Ticket::class)
            ->where('chatable_id', $ticket->id)
            ->first();
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
