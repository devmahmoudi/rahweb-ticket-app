<?php

namespace App\Livewire\Role;

use App\Enums\User\UserType;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AddUser extends Component
{
    public Role $role;

    public Collection $users;

    public string $search = '';

    public function add(User $user)
    {
        $repository = app()->make(UserRepository::class);

        $repository->update($user, ['role_id' => $this->role->id])?
            session()->flash('alert-success', 'کاربر اضافه شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('role.users', $this->role));
    }

    public function remove(User $user)
    {
        $repository = app()->make(UserRepository::class);

        $repository->update($user, ['role_id' => null])?
            session()->flash('alert-success', 'کاربر از نقش حذف شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('role.users', $this->role));
    }

    public function updatedSearch()
    {
        $this->users = User::where('type', '!=', UserType::CUSTOMER->value)
            ->where(function ($query){
              $query->where('name', 'like', $this->search . '%')
                  ->orWhere('email', 'like', $this->search . '%');
            })
            ->get();
    }

    public function mount(UserRepository $repository)
    {
        $this->authorize('update', $this->role);

        $this->users = $repository->allExceptCustomers();
    }

    public function render(UserRepository $repository)
    {
        return view('livewire.pages.role.add-user');
    }
}
