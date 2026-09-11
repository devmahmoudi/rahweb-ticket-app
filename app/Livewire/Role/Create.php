<?php

namespace App\Livewire\Role;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(['required', 'unique:' . Role::class])]
    public string $name = '';

    public function mount()
    {
        $this->authorize('create', Role::class);
    }

    public function store()
    {
        $this->validate();

        $repository = app()->make(RoleRepository::class);

        if(! $role = $repository->store($this->only(['name']))){
            session()->now('alert-danger', 'وجود خطا در سرور');

            return;
        }

        session()->flash('alert-success', 'نقش جدید ایجاد شد !');

        $this->redirect(route('role.permission', $role));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.role.create');
    }
}
