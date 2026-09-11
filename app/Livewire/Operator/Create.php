<?php

namespace App\Livewire\Operator;

use App\Enums\User\UserType;
use App\Models\Role;
use App\Models\User;
use App\Models\Workgroup;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkgroupRepository;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(['required', 'email', 'max:255'])]
    public string $email;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name;

    #[Validate(['nullable', 'string', 'min:8'])]
    public string $password;

    #[Validate([
        'workgroup_ids' => ['nullable', 'array'],
        'workgroup_ids.*' => [
            'numeric',
            "exists:" . Workgroup::class . ',id',
        ]
    ])]
    public array $workgroup_ids;

    #[Validate(['required', 'numeric', 'exists:' . Role::class . ',id'])]
    public int $role_id;

    public function store()
    {
        $this->validate();

        $repository = app()->make(UserRepository::class);

        $this->password = Hash::make($this->password);

        ($user = $repository->create($this->only(['email', 'name', 'role_id', 'password']))) &&
        $user->workgroups()->sync($this->workgroup_ids) ?
            session()->flash('alert-success', 'اوپراتور جدید ایجاد شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور');

        $repository->update($user, ['type' => UserType::OPERATOR->value]);

        $this->redirect(route('operator.index'));
    }

    public function mount()
    {
        $this->authorize('create', User::class);
    }

    public function render(
        WorkgroupRepository $workgroupRepository,
        RoleRepository $roleRepository,
    )
    {
        return view('livewire.pages.operator.create')
            ->with('workgroups', $workgroupRepository->all(['id', 'name']))
            ->with('roles', $roleRepository->all(['id', 'name']));
    }
}
