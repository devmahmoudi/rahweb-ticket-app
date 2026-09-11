<?php

namespace App\Livewire\Meeting;

use App\Models\Meeting;
use App\Repositories\MeetingRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    private MeetingRepository $repository;

    public function delete(Meeting $meeting)
    {
        $this->repository->delete($meeting)?
            session()->now('alert-success', 'جلسه حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function __construct()
    {
        $this->repository = app()->make(MeetingRepository::class);
    }

    public function mount()
    {
        $this->authorize('viewAny', Meeting::class);
    }

    public function render()
    {
        return view('livewire.pages.meeting.index')
            ->with('meetings', $this->repository->paginate());
    }
}
