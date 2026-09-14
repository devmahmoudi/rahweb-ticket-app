<?php

namespace App\Livewire\Ticket;

use App\Enums\User\UserType;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use App\TicketStateManagement\TicketState;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    private TicketRepository $ticketRepository;

    #[Url]
    public string $status = '';

    public function __construct()
    {
        $this->ticketRepository = app()->make(TicketRepository::class);
    }

    public function openChat(Ticket $ticket)
    {
        if ($ticket->user_id != auth()->id()) {
            $this->ticketRepository->accept($ticket);
        }

        $repository = app()->make(TicketRepository::class);

        $this->redirect(route('chat', $repository->findRelevantChat($ticket)));
    }

    public function delete(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $this->ticketRepository->delete($ticket);
    }

    #[On('ticket-assigned')]
    public function refreshAfterAssignment(): void
    {
    }

    public function mount()
    {
        $this->authorize('viewAny', Ticket::class);

    }

    public function render()
    {
        if(auth()->user()->type == UserType::CUSTOMER->value)
            $tickets = $this->ticketRepository->getWithStatusScope($this->status ?: TicketState::PENDING->value);
        else
            $tickets = $this->ticketRepository->getWithStatusScope($this->status);

        return view('livewire.pages.ticket.index')
            ->with('tickets', $tickets);
    }
}
