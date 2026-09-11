<?php

namespace App\Livewire\Cartable;

use App\Events\TaskClosed;
use App\Models\Task;
use App\Repositories\Message\MessageRepository;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Tasks extends Component
{
    public Collection $tasks;

    protected function getListeners()
    {
        return [
            "echo-private:user." . auth()->id() . ",TaskCreated" => 'pushNewTask',
            "echo-private:user." . auth()->id() . ",TaskClosed" => 'removeTask',
            "echo-private:user." . auth()->id() . ",TaskReferred" => 'taskReferredListener'
        ];
    }

    public function pushNewTask($event)
    {
        $task = $event['task'];

        $repository = app()->make(TaskRepository::class);

        if($task = $repository->find($task['id'])){
            $this->tasks->push($task);

            $this->tasks = $this->tasks->sortByDesc('created_at');
        }
    }

    public function taskReferredListener($event)
    {
        if($event['origin_id'] == auth()->id())
            $this->removeTask($event);
        elseif($event['destination_id'] == auth()->id())
            $this->pushNewTask($event);
    }

    /**
     * Removes the task which sent with event from list
     *
     * @param $event
     * @return void
     */
    public function removeTask($event)
    {
        foreach ($this->tasks as $key => $task)
            if($task->id == $event['task']['id']){
                $this->tasks->forget($key);

                return;
            }
    }

    /**
     * Open task's relevant chat
     *
     * @param Task $task
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function open(Task $task)
    {
        $repository = app()->make(TaskRepository::class);

        $this->redirect(route('chat', $repository->findRelevantChat($task)));
    }

    public function sendCloseInquiry(Task $task)
    {
        $taskRepository = app()->make(TaskRepository::class);

        $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $taskRepository->findRelevantChat($task)]);

        $messageRepository->createConfirmCloseTaskMessage($task);

        session()->now('alert-focus', 'task');

        session()->now('alert-success', 'پیام درخواست بستن تیکت ارسال شد!');
    }

    /**
     * Change task status to closed
     *
     * @param Task $task
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function close(Task $task)
    {
        $this->authorize('close', $task);

        $repository = app()->make(TaskRepository::class);

        if($repository->close($task))
            TaskClosed::dispatch($task);
    }

    public function mount(TaskRepository $taskRepository)
    {
        $this->authorize('viewAny', Task::class);

        $this->tasks = $taskRepository->allNotClosedTasks(false)
            ->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.pages.cartable.tasks')
            ->with('tasks', $this->tasks);
    }
}
