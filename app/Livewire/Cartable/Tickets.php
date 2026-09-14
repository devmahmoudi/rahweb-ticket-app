<?php

namespace App\Livewire\Cartable;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Tickets extends Component
{
    public Collection $tickets;

    public function getListeners()
    {
        $listeners = [];

        foreach (auth()->user()->workgroups as $workgroup){
            $listeners["echo-private:workgroup.{$workgroup->id},NewTicket"] = 'newTicket';
        }

        $listeners["echo-private:user." . auth()->id() . ",NewTicket"] = 'newTicket';

        return $listeners;
    }

    public function newTicket($event)
    {
        $this->tickets = app()->make(TicketRepository::class)
            ->pendingTickets(false)
            ->sortByDesc('created_at');
    }

    #[On('ticket-assigned')]
    public function refreshAfterAssignment(): void
    {
        $this->tickets = app()->make(TicketRepository::class)
            ->pendingTickets(false)
            ->sortByDesc('created_at');
    }

    /**
     * Will execute when TicketAccepted event dispatches
     *
     * @param $event
     * @return void
     */
    public function removeTicket($event)
    {
        foreach ($this->tickets as $key => $ticket)
            if($ticket->id == $event['ticket']['id']){
                $this->tickets->forget($key);

                return;
            }
    }

    public function open(Ticket $ticket)
    {
        if(!$ticket->recipient_id){
            app(TicketRepository::class)->accept($ticket);
        }

        if($ticket->recipient_id == auth()->id()){
            $repository = app()->make(TicketRepository::class);

            $this->redirect(route('chat', $repository->findRelevantChat($ticket)));
        }
    }

    public function mount(TicketRepository $ticketRepository)
    {
        $this->authorize('viewAny', Ticket::class);

        $this->tickets =
            $ticketRepository->cartableTickets(false)
                ->sortByDesc('created_at');

    }

    public function render()
    {
        return view('livewire.pages.cartable.tickets')
            ->with('tickets', $this->tickets);
    }
}
