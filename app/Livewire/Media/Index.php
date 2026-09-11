<?php

namespace App\Livewire\Media;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    private MediaRepository $mediaRepository;

    public function download(Media $media)
    {
        $this->authorize('download', $media);

        return Storage::disk(config('media.disk'))->download($media->path);
    }

    public function delete(Media $media)
    {
        $this->authorize('delete', $media);

        $this->mediaRepository->delete($media)?
            session()->now('alert-success', 'فایل حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function __construct()
    {
        $this->mediaRepository = app()->make(MediaRepository::class);
    }

    public function mount()
    {
        $this->authorize('viewAny', Media::class);
    }

    public function render()
    {
        return view('livewire.pages.media.index')
            ->with('medias', $this->mediaRepository->paginate());
    }
}
