<?php

namespace App\Livewire\Operator;

use App\Enums\User\UserType;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Models\Workgroup;
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

    public function store()
    {
        $this->validate();

        $repository = app()->make(UserRepository::class);

        $this->password = Hash::make($this->password);

        $mailConfigured = config('mail.default') !== 'log';

        $user = $repository->create(array_merge(
            $this->only(['email', 'name', 'password']),
            ['type' => UserType::CUSTOMER->value]
        ));

        if (! $mailConfigured) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        } else {
            event(new Registered($user));
        }

        $user->workgroups()->sync($this->workgroup_ids);
        $repository->update($user, ['type' => UserType::OPERATOR->value]);

        session()->flash('alert-success', 'اوپراتور جدید ایجاد شد !');

        $this->redirect(route('operator.index'));
    }

    public function mount()
    {
        $this->authorize('create', User::class);
    }

    public function render(WorkgroupRepository $workgroupRepository)
    {
        return view('livewire.pages.operator.create')
            ->with('workgroups', $workgroupRepository->all(['id', 'name']));
    }
}
