<?php

namespace App\Http\Controllers;

use App\Events\TicketClosed;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CloseTicketController extends Controller
{
    public function __construct(
        public TicketRepository $ticketRepository
    ){}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $this->ticketRepository->close($ticket) ?
            session()->flash('alert-success', 'تیکت بسته شد !'):
            session()->flash('alert-danger', 'خطایی پیش آمده است !');

        broadcast(new TicketClosed($ticket))->toOthers();

        return redirect()->route('ticket.index', ['status' => 'closed']);
    }
}
