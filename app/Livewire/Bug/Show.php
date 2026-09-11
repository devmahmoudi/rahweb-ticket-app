<?php

namespace App\Livewire\Bug;

use App\Enums\Bug\BugStatus;
use App\Models\Bug;
use App\Repositories\BugRepository;
use Livewire\Component;

class Show extends Component
{
    private BugRepository $bugRepository;

    public Bug $bug;

    public function changeStatus()
    {
        $this->authorize('changeStatus', $this->bug);

        $this->bugRepository->update($this->bug, [
            'status' => ($this->bug->status == BugStatus::PENDING->value ?
                BugStatus::FIXED->value:
                BugStatus::PENDING->value)
        ])?
            session()->flash('alert-success', 'تغییر وضعیت انجام شد.'):
            session()->flash('alert-success', 'وجود خطا در سرور !');

        $this->redirect(route('bug.index'));
    }

    public function __construct()
    {
        $this->bugRepository = app()->make(BugRepository::class);
    }

    public function mount()
    {
        $this->authorize('view', $this->bug);
    }

    public function render()
    {
        return view('livewire.pages.bug.show');
    }
}
