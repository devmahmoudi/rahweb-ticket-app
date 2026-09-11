<?php

namespace App\Livewire\Task;

use App\Enums\Task\TaskType;
use App\Enums\User\UserType;
use App\Events\TaskCreated;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    private UserRepository $userRepository;

    private TaskRepository $taskRepository;

    #[Validate(['required', 'string', 'max:255'])]
    public string $title;

    #[Validate(['required', 'exists:users,id'])]
    public int $recipient_id;

    #[Validate(['required'])]
    public string $description;

    public function store()
    {
        $this->validate();

        ($task = $this->taskRepository->create(
            array_merge(
                $this->only(['title', 'recipient_id', 'description']),
                ['creator_id' => auth()->id()]
            )
        )) ?
            session()->flash('alert-success', 'وظیفه چدید ایجاد شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        broadcast(new TaskCreated($task))->toOthers();

        $this->redirect(route('task.index', ['type' => TaskType::SUBMIT->value]));
    }

    public function __construct()
    {
        $this->userRepository = app()->make(UserRepository::class);

        $this->taskRepository = app()->make(TaskRepository::class);
    }

    public function mount()
    {
        $this->authorize('create', Task::class);
    }

    public function render()
    {
        return view('livewire.pages.task.create')
            ->with('users', User::where('type', '!=', UserType::CUSTOMER->value)->where('id', '!=', auth()->id())->get());
    }
}
