<?php

namespace App\TicketStateManagement;

use App\Enums\User\UserType;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class BulkTicketTransitionService
{
    public function authorizeBulkTransition(User $user, bool $bulkMode, array $selectedTicketIds, string $action): void
    {
        abort_unless($user->isOperator() || $user->isSuperadmin(), 403);

        abort_unless(in_array($action, $this->bulkActions($bulkMode, $selectedTicketIds), true), 422);
    }

    public function bulkActions(bool $bulkMode, array $selectedTicketIds): array
    {
        if (! $bulkMode || $selectedTicketIds === []) {
            return [];
        }

        $actionMap = [
            TicketState::PENDING->value => ['claim'],
            TicketState::ACCEPTED->value => ['delegate', 'reject'],
            TicketState::DELEGATED->value => ['publish', 'reject'],
            TicketState::WEBSERVICE->value => [],
            TicketState::REJECTED->value => [],
        ];

        $tickets = Ticket::query()->whereIn('id', $selectedTicketIds)->get();
        $actions = null;

        foreach ($tickets as $ticket) {
            $actions = $actions === null
                ? $actionMap[$ticket->status] ?? []
                : array_values(array_intersect($actions, $actionMap[$ticket->status] ?? []));
        }

        return $actions ?? [];
    }

    public function applyBulkTransition(User $actor, array $selectedTicketIds, string $action, ?int $targetId = null): void
    {
        $tickets = Ticket::query()->whereIn('id', $selectedTicketIds)->get();

        if ($tickets->count() !== count($selectedTicketIds)) {
            abort(403);
        }

        $target = null;

        if ($action === 'delegate') {
            $target = User::query()
                ->whereKey($targetId)
                ->where('type', UserType::SUPERADMIN->value)
                ->firstOrFail();
        }

        foreach ($tickets as $ticket) {
            Gate::authorize('update', $ticket);

            match ($action) {
                'claim' => $ticket->stateManagement()->claim($actor),
                'delegate' => $ticket->stateManagement()->delegateTo($actor, $target),
                'publish' => $ticket->stateManagement()->publishToWebService($actor),
                'reject' => $ticket->stateManagement()->reject(),
                default => abort(422, 'Unknown ticket transition.'),
            };
        }
    }
}
