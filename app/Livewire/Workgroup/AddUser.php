<?php

namespace App\Livewire\Workgroup;

use App\Enums\User\UserType;
use App\Models\User;
use App\Models\Workgroup;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AddUser extends Component
{
    public Collection $users;

    public Workgroup $workgroup;

    public string $search = '';

    public function detachUser(User $user)
    {
        $this->authorize('update', $this->workgroup);

        $this->workgroup->users()->detach($user)?
            session()->now('alert-success', 'کاربر از این گروه کاری حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function attachUser(User $user)
    {
        $this->workgroup->users()->attach($user);

        session()->now('alert-success', 'کاربر به گروه کاری اضافه شد !');
    }

    public function updatedSearch()
    {
        $this->users = User::where('type', UserType::OPERATOR->value)
            ->where(function ($query){
                $query->where('name', 'like', $this->search . '%')
                    ->orWhere('email', 'like', $this->search . '%');
            })
            ->get();
    }

    public function mount(UserRepository $repository)
    {
        $this->authorize('update', $this->workgroup);

        $this->users = $repository->allOperators();
    }

    public function render(UserRepository $repository)
    {

        return view('livewire.pages.workgroup.add-user');
    }
}
