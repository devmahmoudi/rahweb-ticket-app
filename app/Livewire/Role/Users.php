<?php

namespace App\Livewire\Role;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Users extends Component
{
    public Role $role;

    public function detachUser(User $user)
    {
        $repository = app()->make(UserRepository::class);

        $repository->update($user, ['role_id' => null]) ?
            session()->now('alert-success', "کاربر از نقش {$this->role->name} حذف شد ! ") :
            session()->now('alert-danger', "وجود خطا در سرور");
    }

    public function mount()
    {
        $this->authorize('viewAny', Role::class);
    }

    public function render()
    {
        return view('livewire.pages.role.users')
            ->with('users', $this->role->users);
    }
}
