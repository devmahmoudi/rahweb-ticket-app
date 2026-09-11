<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TaskStatus extends Component
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
                 <span @class([
                    'badge',
                    'bg-primary' => ($status == \App\Enums\Task\TaskStatus::PENDING->value),
                    'bg-success' => ($status == \App\Enums\Task\TaskStatus::CLOSED->value),
                    'bg-warning' => ($status == \App\Enums\Task\TaskStatus::SENT->value),
                 ])>
                    {{ $status }}
                 </span>
    </div>
blade;
    }
}
