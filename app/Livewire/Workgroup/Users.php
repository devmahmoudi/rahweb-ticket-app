<?php

namespace App\Livewire\Workgroup;

use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Users extends Component
{
    public Workgroup $workgroup;

    public Collection $users;

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

    public function mount()
    {
        $this->authorize('view', $this->workgroup);

        $this->users = $this->workgroup->users;
    }

    public function render()
    {
        return view('livewire.pages.workgroup.users')
            ->with('users', $this->users);
    }
}
