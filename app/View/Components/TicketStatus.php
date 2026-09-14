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
         @case(\App\TicketStateManagement\TicketState::PENDING->value)
             <span class="badge bg-warning">در انتظار رسیدگی</span>
             @break
         @case(\App\TicketStateManagement\TicketState::ACCEPTED->value)
             <span class="badge bg-primary">در حال رسیدگی</span>
             @break
         @case(\App\TicketStateManagement\TicketState::DELEGATED->value)
             <span class="badge bg-info">ارجاع شده</span>
             @break
         @case(\App\TicketStateManagement\TicketState::WEBSERVICE->value)
             <span class="badge bg-secondary">ارسال شده به وب سرویس</span>
             @break
         @case(\App\TicketStateManagement\TicketState::REJECTED->value)
             <span class="badge bg-danger">رد شده</span>
             @break
     @endswitch
</div>
blade;
    }
}
