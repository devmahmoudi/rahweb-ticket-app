<?php

namespace App\Livewire\Bug;

use App\Models\Bug;
use App\Repositories\BugRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Bug $bug;

    private BugRepository $bugRepository;

    #[Validate(['required', 'max:255'])]
    public string $title;

    #[Validate(['required', 'string'])]
    public string $description;

    public function update()
    {
        $this->validate();

        $this->bugRepository->update($this->bug, $this->only(['title', 'description']))?
            session()->flash('alert-success', 'ویرایش شد.'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('bug.index'));
    }

    public function __construct()
    {
        $this->bugRepository = app()->make(BugRepository::class);
    }

    public function mount()
    {
        $this->authorize('update', $this->bug);

        $this->title = $this->bug->title;

        $this->description = $this->bug->description;
    }

    public function render()
    {
        return view('livewire.pages.bug.edit');
    }
}
