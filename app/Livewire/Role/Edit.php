<?php

namespace App\Livewire\Role;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Role $role;

    public string $name = '';

    public function update()
    {
        $this->validate([
            'name' => ['required', Rule::unique(Role::class)->ignore($this->role->id)]
        ]);

        $repository = app()->make(RoleRepository::class);

        if(!$repository->update($this->role, $this->only(['name']))){
            session()->now('alert-danger', 'وجود خطا در سرور');

            return;
        }

        session()->flash('alert-success', 'ویرایش شد !');

        $this->redirect(route('role.index'));
    }

    public function mount()
    {
        $this->authorize('update', $this->role);

        $this->name = $this->role->name;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.role.edit');
    }
}
