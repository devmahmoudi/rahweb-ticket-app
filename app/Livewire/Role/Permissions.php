<?php

namespace App\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Permissions extends Component
{
    public Role $role;

    public $permissions;

    #[Validate([
        'checkedPermissions' => 'nullable',
        'checkedPermissions.*' => [
            'exists:' . Permission::class . ',id'
        ],
    ])]
    public array $checkedPermissions;

    /**
     * @return void
     */
    public function save():void
    {
        $this->validate();

        $this->role->permissions()->sync($this->checkedPermissions);

        $this->role->save();

        session()->flash('alert-success', 'ذخیره شد !');

        $this->redirect(route('role.index'));
    }

    public function mount(PermissionRepository $repository)
    {
        $this->authorize('update', $this->role);

        $this->checkedPermissions = $this->role->permissions->pluck('id')->toArray();

        $this->permissions = $repository->all();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.role.permissions');
    }
}
