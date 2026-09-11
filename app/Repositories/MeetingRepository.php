<?php

namespace App\Repositories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Collection;

class MeetingRepository
{
    public function delete(Meeting $meeting):bool
    {
        return $meeting->delete();
    }

    public function create(array $data):Meeting|false
    {
        return Meeting::create($data);
    }

    public function update(Meeting $meeting, array $data):bool
    {
        return $meeting->update($data);
    }

    public function paginate(int $perPage = 20)
    {
        return Meeting::paginate($perPage);
    }

    public function all(array $columns = ['*']):Collection
    {
        return Meeting::all($columns);
    }
}
