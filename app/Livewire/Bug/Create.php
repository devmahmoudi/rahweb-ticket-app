<?php

namespace App\Livewire\Bug;

use App\Models\Bug;
use App\Repositories\BugRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    private BugRepository $bugRepository;

    #[Validate(['required', 'max:255'])]
    public string $title;

    #[Validate(['required', 'string'])]
    public string $description;

    public function store()
    {
        $this->validate();

        $this->bugRepository->create(array_merge(
            $this->only(['title', 'description']),
            ['creator_id' => auth()->id()]
        ))?
            session()->flash('alert-success', 'باگ جدید ثبت شد.'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('bug.index'));
    }

    public function __construct()
    {
        $this->bugRepository = app()->make(BugRepository::class);
    }

    public function mount()
    {
        $this->authorize('create', Bug::class);
    }

    public function render()
    {
        return view('livewire.pages.bug.create');
    }
}
