<?php

namespace App\Livewire\Bug;

use App\Enums\Bug\BugStatus;
use App\Models\Bug;
use App\Repositories\BugRepository;
use Livewire\Component;

class Index extends Component
{
    private BugRepository $bugRepository;

    public function delete(Bug $bug)
    {
        $this->authorize('delete', $bug);

        $this->bugRepository->delete($bug)?
            session()->now('alert-success', 'حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function changeStatus(Bug $bug)
    {
        $this->authorize('changeStatus', $bug);

        $this->bugRepository->update($bug, [
            'status' => ($bug->status == BugStatus::PENDING->value ?
                BugStatus::FIXED->value:
                BugStatus::PENDING->value)
        ])?
            session()->now('alert-success', 'تغییر وضعیت انجام شد.'):
            session()->now('alert-success', 'وجود خطا در سرور !');
    }

    public function __construct()
    {
        $this->bugRepository = app()->make(BugRepository::class);
    }

    public function render()
    {
        return view('livewire.pages.bug.index')
            ->with('bugs', $this->bugRepository->paginate());
    }
}
