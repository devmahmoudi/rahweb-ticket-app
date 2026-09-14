<?php

namespace App\Livewire\Operator;

use App\Models\User;
use App\Models\Workgroup;
use App\Repositories\WorkgroupRepository;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public User $operator;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name;

    #[Validate(['nullable', 'string', 'min:8'])]
    public string $password;

    #[Validate([
        'workgroup_ids' => ['nullable', 'array'],
        'workgroup_ids.*' => [
            'numeric',
            "exists:" . Workgroup::class . ',id',
        ]
    ])]
    public array $workgroup_ids;

    public function update()
    {
        $this->validate();

        $fields = $this->only(['name']);

        if(!empty($this->password))
            $fields['password'] = Hash::make($this->password);

        $this->operator->update($fields) &&
        $this->operator->workgroups()->sync($this->workgroup_ids) ?
            session()->flash('alert-success', 'اوپراتور ویرایش شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور');

        $this->redirect(route('operator.index'));
    }

    public function mount()
    {
        $this->authorize('update', $this->operator);

        $this->name = $this->operator->name;

        $this->workgroup_ids = $this->operator->workgroups->pluck('id')->toArray();

    }

    public function render(WorkgroupRepository $workgroupRepository)
    {
        return view('livewire.pages.operator.edit')
            ->with('workgroups', $workgroupRepository->all(['id', 'name']));
    }
}
