<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaRepository
{
    public function paginate(int $perPage = 20)
    {
        return Media::paginate($perPage);
    }

    public function create(array $data):Media|false
    {
        return Media::create($data);
    }

    public function delete(Media $media):bool
    {
        return
            Storage::disk(config('media.disk'))->delete($media->path)
            and
            $media->delete()
            ??
            false;
    }
}
