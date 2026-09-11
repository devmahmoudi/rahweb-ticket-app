<?php

namespace App\Livewire\Role;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function mount()
    {
        $this->authorize('viewAny', Role::class);
    }

    public function delete(Role $role)
    {
        $this->authorize('delete', $role);

        $repository = app()->make(RoleRepository::class);

        $role->users()->update(['role_id' => null]);

        $role->permissions()->detach();

        $repository->delete($role) ?
            session()->now('alert-success', 'نقش حذف شد !') :
            session()->now('alert-danger', 'وجود خطا در سرور');
    }

    #[Layout('layouts.app')]
    public function render(RoleRepository $repository)
    {
        return view('livewire.pages.role.index', [
            'roles' => Role::paginate()
        ]);
    }
}
