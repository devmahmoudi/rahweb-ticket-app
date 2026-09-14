<?php

namespace App\Livewire\Ticket;

use App\Enums\User\UserType;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use App\TicketStateManagement\TicketState;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    public const CARTABLE_FILTER = 'cartable';

    private TicketRepository $ticketRepository;

    #[Url]
    public string $status = '';

    public ?int $delegateTargetId = null;

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

    public function getListeners(): array
    {
        $listeners = [];

        foreach (auth()->user()->workgroups as $workgroup) {
            $listeners["echo-private:workgroup.{$workgroup->id},NewTicket"] = 'newTicket';
        }

        $listeners["echo-private:user." . auth()->id() . ",NewTicket"] = 'newTicket';

        return $listeners;
    }

    public function newTicket($event = null): void
    {
        // The event triggers a Livewire rerender using the current filter.
    }

    public function transition(Ticket $ticket, string $action, ?int $targetId = null): void
    {
        $this->authorize('update', $ticket);

        $actor = auth()->user();

        match ($action) {
            'claim' => $ticket->stateManagement()->claim($actor),
            'delegate' => $ticket->stateManagement()->delegateTo(
                $actor,
                User::query()->whereKey($targetId)->where('type', UserType::SUPERADMIN->value)->firstOrFail(),
            ),
            'publish' => $ticket->stateManagement()->publishToWebService($actor),
            'reject' => $ticket->stateManagement()->reject(),
            default => abort(422, 'Unknown ticket transition.'),
        };
    }

    public function delegate(Ticket $ticket): void
    {
        $this->transition($ticket, 'delegate', $this->delegateTargetId);
    }

    public function mount()
    {
        $this->authorize('viewAny', Ticket::class);

    }

    public function render()
    {
        $canViewCartable = \Illuminate\Support\Facades\Gate::allows('cartable');
        $selectedStatus = $this->status ?: ($canViewCartable ? self::CARTABLE_FILTER : TicketState::PENDING->value);

        if ($selectedStatus === self::CARTABLE_FILTER) {
            abort_unless(\Illuminate\Support\Facades\Gate::allows('cartable'), 403);
        }

        $ticketCounts = collect(TicketState::cases())
            ->mapWithKeys(fn (TicketState $state) => [
                $state->value => $this->ticketRepository->count(TicketState: $state->value),
            ]);

        if ($selectedStatus === self::CARTABLE_FILTER) {
            $tickets = $this->ticketRepository->cartableTickets();
        } elseif(auth()->user()->type == UserType::CUSTOMER->value)
            $tickets = $this->ticketRepository->getWithStatusScope($selectedStatus);
        else
            $tickets = $this->ticketRepository->getWithStatusScope($selectedStatus);

        return view('livewire.pages.ticket.index')
            ->with('ticketCounts', $ticketCounts)
            ->with('cartableCount', $canViewCartable ? $this->ticketRepository->cartableTickets(false)->count() : 0)
            ->with('selectedStatus', $selectedStatus)
            ->with('tickets', $tickets);
    }
}
