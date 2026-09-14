<?php

namespace App\Livewire\Ticket;

use App\Models\Ticket;
use App\Models\Workgroup;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Validate(['required', 'max:255', 'string'])]
    public string $title;

    #[Validate(['required', 'exists:' . Workgroup::class . ',id'])]
    public int $workgroup_id;

    #[Validate(['required'])]
    public string $description;

    #[Validate(['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,gif,bmp'])]
    public $attachment;

    public function __construct()
    {
    }

    public function store()
    {
        $this->validate();

        $ticketData = array_merge(
            $this->only(['title', 'workgroup_id', 'description']),
            ['user_id' => auth()->id()]
        );

        if ($this->attachment) {
            $attachmentPath = $this->attachment->storeAs(
                'ticket-attachments',
                Str::uuid() . '.' . $this->attachment->getClientOriginalExtension(),
                'public'
            );

            $ticketData['attachment_path'] = $attachmentPath;
        }

        Ticket::create($ticketData) ?
            session()->flash('alert-success', 'تیکت جدید با موفقیت ثبت شد !') :
            session()->flash('alert-danger', 'وجود خطا در سرور ! لطفا زمان دیگری امتحان کنید.');

        $this->redirect(route('ticket.index'));
    }

    public function render()
    {
        return view('livewire.pages.ticket.create')
            ->with('workgroups', Workgroup::all());
    }
}
