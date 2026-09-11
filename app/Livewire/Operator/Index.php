<?php

namespace App\Livewire\Operator;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    public function delete(User $user)
    {
        $this->authorize('delete', $user);

        $repository = app()->make(UserRepository::class);

        $repository->delete($user) ?
            session()->now('alert-success', 'کاربر حذف شد !') :
            session()->now('alert-danger', 'وجود خطا در سامانه !') ;
    }

    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    public function render(UserRepository $repository)
    {
        return view('livewire.pages.operator.index')
            ->with('users', $repository->allOperators());
    }
}
