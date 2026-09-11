<?php

namespace App\Repositories;

use App\Models\Bug;

class BugRepository
{
    public function paginate(int $perPage = 20)
    {
        return Bug::paginate($perPage);
    }

    public function delete(Bug $bug):bool
    {
        return $bug->delete();
    }

    public function create($data):Bug|false
    {
        return Bug::create($data);
    }

    public function update(Bug $bug, array $data):bool
    {
        return $bug->update($data);
    }
}
