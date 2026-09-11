<?php

namespace App\Livewire\Media;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Upload extends Component
{
    use WithFileUploads;

    private MediaRepository $mediaRepository;

    #[Validate(['nullable', 'max:255'])]
    public string $name;

    #[Validate(['file'])]
    public $file;

    public function store()
    {
        $this->validate();

        if(!$path = $this->file->store(config('media.directory'), config('media.disk')))
            session()->flash('alert-danger', 'وجود خطا در فرایند ذخیره سازی فایل !');

        $this->mediaRepository->create([
            'path' => $path,
            'name' => $this->name ?? $this->file->getClientOriginalName(),
            'creator_id' => auth()->id(),
        ])?
            session()->flash('alert-success', 'فایل جدید ذخیره شد.'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('media.index'));
    }

    public function __construct()
    {
        $this->mediaRepository = app()->make(MediaRepository::class);
    }

    public function mount()
    {
        $this->authorize('upload', Media::class);
    }

    public function render()
    {
        return view('livewire.pages.media.upload');
    }
}
