<?php

namespace App\Livewire\Workgroup;

use App\Models\Workgroup;
use App\Repositories\WorkgroupRepository;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function delete(Workgroup $workgroup)
    {
        $this->authorize('delete', $workgroup);

        $repository = app()->make(WorkgroupRepository::class);

        $repository->delete($workgroup)?
            session()->now('alert-success', 'گروه کاری حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function mount()
    {
        $this->authorize('viewAny', Workgroup::class);
    }

    public function render(WorkgroupRepository $repository)
    {
        return view('livewire.pages.workgroup.index')
            ->with('workgroups', $repository->paginate());
    }
}
