<?php

namespace App\View\Components;

use App\Models\Task;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TaskReferredMessage extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Task $task,
        public User $origin,
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.task-referred-message')
            ->with('task', $this->task)
            ->with('origin', $this->origin);
    }
}
