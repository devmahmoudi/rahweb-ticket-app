<?php

namespace App\View\Components;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TaskClosedMessage extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Task $task,
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.task-closed-message')
            ->with('task', $this->task);
    }
}
