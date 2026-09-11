<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketStatus extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $status
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
<div>
     @switch($status)
         @case(\App\Enums\Ticket\TicketStatus::WAITING->value)
             <span class="badge bg-warning">در انتظار پاسخگو</span>
             @break
         @case(\App\Enums\Ticket\TicketStatus::PENDING->value)
             <span class="badge bg-primary">در حال رسیدگی</span>
             @break
         @case(\App\Enums\Ticket\TicketStatus::CLOSED->value)
             <span class="badge bg-success">بسته شده</span>
             @break
     @endswitch
</div>
blade;
    }
}
