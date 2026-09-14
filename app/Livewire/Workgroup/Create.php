<?php

namespace App\Livewire\Workgroup;

use App\Models\Workgroup;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(['required', 'unique:' . Workgroup::class])]
    public string $name = '';

    public function store()
    {
        $this->validate();

        if(! Workgroup::create($this->only(['name']))){
            session()->now('alert-danger', 'وجود خطا در سرور');

            return;
        }

        session()->flash('alert-success', 'گروه کاری جدید ایجاد شد !');

        $this->redirect(route('workgroup.index'));
    }

    public function mount()
    {
        $this->authorize('create', Workgroup::class);
    }

    public function render()
    {
        return view('livewire.pages.workgroup.create');
    }
}
