<?php

namespace App\Http\Controllers;

use App\Enums\Task\TaskType;
use App\Events\TaskClosed;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CloseTaskController extends Controller
{
    public function __construct(
        private TaskRepository $taskRepository
    )
    {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Task $task)
    {
        Gate::authorize('close', $task);

        $this->taskRepository->close($task) ?
            session()->flash('alert-success', 'وظیفه بسته شد !'):
            session()->flash('alert-danger', 'خطایی پیش آمده است !');

        broadcast(new TaskClosed($task))->toOthers();

        return redirect()->route('task.index', ['type' => TaskType::SUBMIT->value]);
    }
}
