<?php

namespace App\Livewire\Ticket;

use App\Models\Workgroup;
use App\Repositories\TicketRepository;
use App\Repositories\WorkgroupRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    private WorkgroupRepository $workgroupRepository;

    private TicketRepository $ticketRepository;

    #[Validate(['required', 'max:255', 'string'])]
    public string $title;

    #[Validate(['required', 'exists:' . Workgroup::class . ',id'])]
    public int $workgroup_id;

    #[Validate(['required'])]
    public string $description;

    public function __construct()
    {
        $this->workgroupRepository = app()->make(WorkgroupRepository::class);

        $this->ticketRepository = app()->make(TicketRepository::class);
    }

    public function store()
    {
        $this->validate();

        $this->ticketRepository->create(
            array_merge(
                $this->only(['title', 'workgroup_id', 'description']),
                ['user_id' => auth()->id()]
            )
        ) ?
            session()->flash('alert-success', 'تیکت جدید با موفقیت ثبت شد !') :
            session()->flash('alert-danger', 'وجود خطا در سرور ! لطفا زمان دیگری امتحان کنید.');

        $this->redirect(route('ticket.index'));
    }

    public function render()
    {
        return view('livewire.pages.ticket.create')
            ->with('workgroups', $this->workgroupRepository->all());
    }
}
