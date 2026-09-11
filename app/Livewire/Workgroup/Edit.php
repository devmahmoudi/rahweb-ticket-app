<?php

namespace App\Livewire\Workgroup;

use App\Models\Workgroup;
use App\Repositories\WorkgroupRepository;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Workgroup $workgroup;

    public string $name = '';

    public function mount()
    {
        $this->authorize('update', $this->workgroup);

        $this->name = $this->workgroup->name;
    }

    public function update()
    {
        $this->validate([
            'name' => ['required', Rule::unique(Workgroup::class)->ignore($this->workgroup->id)]
        ]);

        $repository = app()->make(WorkgroupRepository::class);

        $repository->update($this->workgroup, $this->only(['name']))?
            session()->flash('alert-success', 'گروه کاری ویرایش شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('workgroup.index'));
    }

    public function render()
    {
        return view('livewire.pages.workgroup.edit');
    }
}
