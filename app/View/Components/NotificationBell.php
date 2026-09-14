<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationBell extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $notifications = collect();

        if (auth()->check()) {
            $notifications = auth()->user()->unreadNotifications()->latest()->take(10)->get();
        }

        return view('components.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $notifications->count(),
        ]);
    }
}
