<?php

namespace App\Livewire\Ticket;

use App\Enums\Ticket\TicketStatus;
use App\Enums\User\UserType;
use App\Models\Chat;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    private TicketRepository $ticketRepository;

    #[Url]
    public string $status = '';

    public Collection $assignmentUsers;

    public ?int $assignmentTicketId = null;

    public ?int $assignmentUserId = null;

    public function __construct()
    {
        $this->ticketRepository = app()->make(TicketRepository::class);
    }

    public function openChat(Ticket $ticket)
    {
        if($ticket->user_id != auth()->id())
            $this->accept($ticket);

        $repository = app()->make(TicketRepository::class);

        $this->redirect(route('chat', $repository->findRelevantChat($ticket)));
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

    public function delete(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $this->ticketRepository->delete($ticket);
    }

    public function closeTicket(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $this->ticketRepository->update($ticket,
            [
                'status' => TicketStatus::CLOSED->value,
                'recipient_id' => null,
            ]) ?
            session()->now('alert-success', 'تیکت بسته شد !') :
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function openAssignmentModal(Ticket $ticket): void
    {
        $this->authorize('assign', $ticket);

        $this->assignmentTicketId = $ticket->id;
        $this->assignmentUserId = null;
    }

    public function assignTicket(): void
    {
        $ticket = Ticket::findOrFail($this->assignmentTicketId);

        $this->authorize('assign', $ticket);

        $this->validate([
            'assignmentUserId' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);

        $target = User::query()
            ->whereKey($this->assignmentUserId)
            ->whereIn('type', [UserType::OPERATOR->value, UserType::ADMIN->value])
            ->firstOrFail();

        if ($target->id === $ticket->recipient_id) {
            $this->addError('assignmentUserId', 'کاربر مقصد باید با پاسخگوی فعلی متفاوت باشد.');

            return;
        }

        $ticket->update(['recipient_id' => $target->id]);
        $this->assignmentTicketId = null;
        $this->assignmentUserId = null;
        session()->now('alert-success', 'تیکت با موفقیت واگذار شد.');
    }

    public function mount()
    {
        $this->authorize('viewAny', Ticket::class);

        $this->assignmentUsers = User::query()
            ->whereIn('type', [UserType::OPERATOR->value, UserType::ADMIN->value])
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        if(auth()->user()->type == UserType::CUSTOMER->value)
            $tickets = $this->status == TicketStatus::CLOSED->value ?
                $this->ticketRepository->closedTickets() :
                $this->ticketRepository->notClosedTickets();
        else
            $tickets = $this->ticketRepository->getWithStatusScope($this->status);

        return view('livewire.pages.ticket.index')
            ->with('tickets', $tickets);
    }
}
