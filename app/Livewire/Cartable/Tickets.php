<?php

namespace App\Livewire\Cartable;

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket;
use App\Repositories\Message\MessageRepository;
use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Tickets extends Component
{
    public Collection $tickets;

    public function getListeners()
    {
        $listeners = [];

        foreach (auth()->user()->workgroups as $workgroup){
            $listeners["echo-private:workgroup.{$workgroup->id},NewTicket"] = 'newTicket';
            $listeners["echo-private:workgroup.{$workgroup->id},TicketAccepted"] = 'removeTicket';
            $listeners["echo-private:workgroup.{$workgroup->id},TicketClosed"] = 'removeTicket';
        }

        return $listeners;
    }

    public function newTicket($event)
    {
        $ticket = $event['ticket'];

        if($ticket = Ticket::find($ticket['id'])){
            $this->tickets->push($ticket);

            $this->tickets = $this->tickets->sortByDesc('created_at');
        }
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
            $this->accept($ticket);
        }

        if($ticket->recipient_id == auth()->id()){
            $repository = app()->make(TicketRepository::class);

            $this->redirect(route('chat', $repository->findRelevantChat($ticket)));
        }
    }

    /**
     * Accept ticket for handling and chat with ticket owner
     *
     * @param Ticket $ticket
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function accept(Ticket $ticket)
    {
        $lock = cache()->lock(config('ticket.accept-cache-lock-prefix') . $ticket->id, 2)->block(2, function() use ($ticket){
            $ticket->fresh();

            if($ticket->status == TicketStatus::WAITING->value){
                $repository = app()->make(TicketRepository::class);

                $repository->accept($ticket);
            }
        });
    }

    public function sendCloseTicketInquiry(Ticket $ticket)
    {
        $ticketRepository = app()->make(TicketRepository::class);

        $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $ticketRepository->findRelevantChat($ticket)]);

        $messageRepository->createConfirmCloseTicketMessage($ticket);

        session()->now('alert-success', 'پیام درخواست بستن تیکت ارسال شد!');
    }

    public function mount(TicketRepository $ticketRepository)
    {
        $this->authorize('viewAny', Ticket::class);

        $this->tickets =
            $ticketRepository->notClosedTickets(false)
                ->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.pages.cartable.tickets')
            ->with('tickets', $this->tickets);
    }
}
