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

    public bool $bulkMode = false;

    public array $selectedTicketIds = [];

    public ?int $bulkDelegateTargetId = null;

    public bool $showBulkDelegateModal = false;

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

    public function toggleBulkMode(): void
    {
        $this->bulkMode = !$this->bulkMode;
        $this->selectedTicketIds = [];
        $this->showBulkDelegateModal = false;
    }

    public function openBulkDelegateModal(): void
    {
        $this->authorizeBulkTransition('delegate');
        $this->showBulkDelegateModal = true;
    }

    public function closeBulkDelegateModal(): void
    {
        $this->showBulkDelegateModal = false;
        $this->bulkDelegateTargetId = null;
    }

    public function applyBulkTransition(string $action, ?int $targetId = null): void
    {
        $this->authorizeBulkTransition($action);

        $tickets = Ticket::query()->whereIn('id', $this->selectedTicketIds)->get();
        if ($tickets->count() !== count($this->selectedTicketIds)) {
            abort(403);
        }

        $actor = auth()->user();
        $target = null;
        if ($action === 'delegate') {
            $target = User::query()
                ->whereKey($targetId)
                ->where('type', UserType::SUPERADMIN->value)
                ->firstOrFail();
        }

        foreach ($tickets as $ticket) {
            $this->authorize('update', $ticket);

            match ($action) {
                'claim' => $ticket->stateManagement()->claim($actor),
                'delegate' => $ticket->stateManagement()->delegateTo($actor, $target),
                'publish' => $ticket->stateManagement()->publishToWebService($actor),
                'reject' => $ticket->stateManagement()->reject(),
                default => abort(422, 'Unknown ticket transition.'),
            };
        }

        $this->selectedTicketIds = [];
        $this->bulkMode = false;
        $this->closeBulkDelegateModal();
    }

    public function applyBulkDelegate(): void
    {
        $this->applyBulkTransition('delegate', $this->bulkDelegateTargetId);
    }

    private function authorizeBulkTransition(string $action): void
    {
        abort_unless(auth()->user()->isOperator() || auth()->user()->isSuperadmin(), 403);

        $availableActions = $this->bulkActions();
        abort_unless(in_array($action, $availableActions, true), 422);
    }

    private function bulkActions(): array
    {
        if (!$this->bulkMode || $this->selectedTicketIds === []) {
            return [];
        }

        $actionMap = [
            TicketState::PENDING->value => ['claim'],
            TicketState::ACCEPTED->value => ['delegate', 'reject'],
            TicketState::DELEGATED->value => ['publish', 'reject'],
            TicketState::WEBSERVICE->value => [],
            TicketState::REJECTED->value => [],
        ];

        $tickets = Ticket::query()->whereIn('id', $this->selectedTicketIds)->get();
        $actions = null;
        foreach ($tickets as $ticket) {
            $actions = $actions === null
                ? $actionMap[$ticket->status] ?? []
                : array_values(array_intersect($actions, $actionMap[$ticket->status] ?? []));
        }

        return $actions ?? [];
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
            ->with('bulkActions', $this->bulkActions())
            ->with('tickets', $tickets);
    }
}
