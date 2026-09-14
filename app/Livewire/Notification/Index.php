<?php

namespace App\Livewire\Notification;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'all';

    public function markAsRead(string $notificationId): void
    {
        $notification = auth()->user()->notifications()->whereKey($notificationId)->firstOrFail();

        $notification->markAsRead();

        $this->resetPage();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()->latest();

        if ($this->filter === 'unread') {
            $notifications = auth()->user()->unreadNotifications()->latest();
        }

        return view('livewire.notification.index', [
            'notifications' => $notifications->paginate(10),
        ]);
    }
}
