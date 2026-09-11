<?php

namespace App\Livewire\Meeting;

use App\Models\Meeting;
use App\Repositories\MeetingRepository;
use App\Repositories\UserRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    private UserRepository $userRepository;

    private MeetingRepository $meetingRepository;

    #[Validate(['required', 'max:255'])]
    public string $title;

    #[Validate(['required'])]
    public string $participants;

    #[Validate(['required', 'exists:customers,id'])]
    public int $customer_id;

    #[Validate(['required', 'date'])]
    public string $date;

    #[Validate(['required', 'string'])]
    public string $text;

    public function store()
    {
        $this->validate();

        $this->meetingRepository->create(array_merge(
            $this->only(['title', 'participants', 'customer_id', 'date', 'text']),
            ['creator_id' => auth()->id()],
        ))?
            session()->flash('alert-success', 'صورت جلسه جدید ثبت شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('meeting.index'));
    }

    public function __construct()
    {
        $this->userRepository = app()->make(UserRepository::class);

        $this->meetingRepository = app()->make(MeetingRepository::class);
    }

    public function mount()
    {
        $this->authorize('create', Meeting::class);
    }

    public function render()
    {
        return view('livewire.pages.meeting.create')
            ->with('users', $this->userRepository->allCustomers());
    }
}
