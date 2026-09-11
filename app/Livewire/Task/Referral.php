<?php

namespace App\Livewire\Task;

use App\Enums\User\UserType;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Referral extends Component
{
    public Task $task;

    private UserRepository $userRepository;

    private TaskRepository $taskRepository;

    #[Validate(['required', 'exists:users,id'])]
    public int $recipient_id;

    public function referral()
    {
        $this->validate();

        $this->taskRepository
            ->referral($this->task, $this->userRepository->find($this->recipient_id)) ?
            session()->flash('alert-success', 'وظیفه ارجاع شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('task.index'));
    }

    public function __construct()
    {
        $this->userRepository = app()->make(UserRepository::class);

        $this->taskRepository = app()->make(TaskRepository::class);
    }

    public function render()
    {
        return view('livewire.pages.task.referral')
            ->with('users', User::where('type', '!=', UserType::CUSTOMER->value)
                ->where('id', '!=', auth()->id())
                ->where('id', '!=', $this->task->creator_id)
                ->get()
            );
    }
}
