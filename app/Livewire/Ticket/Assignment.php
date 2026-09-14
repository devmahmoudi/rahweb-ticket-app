<?php

namespace App\Livewire\Ticket;

use App\Enums\User\UserType;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use Livewire\Attributes\On;
use Livewire\Component;

class Assignment extends Component
{
    public ?int $ticketId = null;

    public ?int $userId = null;

    public function open(int $ticketId): void
    {
        $ticket = Ticket::findOrFail($ticketId);

        $this->authorize('assign', $ticket);

        $this->ticketId = $ticket->id;
        $this->userId = null;
        $this->resetValidation();
    }

    #[On('open-ticket-assignment')]
    public function openFromEvent(int $ticketId): void
    {
        $this->open($ticketId);
    }

    public function assign(): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        $this->authorize('assign', $ticket);

        $this->validate([
            'userId' => ['required', 'integer', 'exists:users,id'],
        ]);

        $target = User::query()
            ->whereKey($this->userId)
            ->whereIn('type', [UserType::OPERATOR->value, UserType::SUPERADMIN->value])
            ->firstOrFail();

        if ($target->id === $ticket->recipient_id) {
            $this->addError('userId', 'کاربر مقصد باید با پاسخگوی فعلی متفاوت باشد.');

            return;
        }

        app(TicketRepository::class)->assignTicket($ticket, $target);

        $this->ticketId = null;
        $this->userId = null;
        session()->now('alert-success', 'تیکت با موفقیت واگذار شد.');
        $this->dispatch('ticket-assigned');
    }

    public function render()
    {
        $users = User::query()
            ->whereIn('type', [UserType::OPERATOR->value, UserType::SUPERADMIN->value])
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        return view('livewire.ticket.assignment', compact('users'));
    }
}
