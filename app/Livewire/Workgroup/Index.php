<?php

namespace App\Livewire\Workgroup;

use App\Models\Workgroup;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function delete(Workgroup $workgroup)
    {
        $this->authorize('delete', $workgroup);

        $workgroup->delete()?
            session()->now('alert-success', 'گروه کاری حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function mount()
    {
        $this->authorize('viewAny', Workgroup::class);
    }

    public function render()
    {
        return view('livewire.pages.workgroup.index')
            ->with('workgroups', Workgroup::paginate());
    }
}
