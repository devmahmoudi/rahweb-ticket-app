<?php

namespace App\Livewire\Meeting;

use App\Models\Meeting;
use App\Repositories\MeetingRepository;
use App\Repositories\UserRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Meeting $meeting;

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

    public function update()
    {
        $this->validate();

        $this->meetingRepository->update(
            $this->meeting,
            $this->only(['title', 'participants', 'customer_id', 'date', 'text'])
        )?
            session()->flash('alert-success', 'ویرایش شد !'):
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
        $this->authorize('update', $this->meeting);

        $this->title = $this->meeting->title;

        $this->participants = $this->meeting->participants;

        $this->customer_id = $this->meeting->customer_id;

        $this->date = $this->meeting->date;

        $this->text = $this->meeting->text;

    }

    public function render()
    {
        return view('livewire.pages.meeting.edit')
            ->with('users', $this->userRepository->allCustomers());
    }
}
