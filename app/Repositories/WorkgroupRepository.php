<?php

namespace App\Repositories;

use App\Models\Workgroup;
use Illuminate\Database\Eloquent\Collection;

class WorkgroupRepository
{
    public function all(array $columns = ['*']):Collection
    {
        return Workgroup::all($columns);
    }

    public function paginate(int $perPage = 10)
    {
        return Workgroup::paginate($perPage);
    }

    public function store(array $data):Workgroup|false
    {
        return Workgroup::create($data);
    }

    public function delete(Workgroup $workgroup):bool
    {
        return $workgroup->delete();
    }

    public function update(Workgroup $workgroup, array $data):bool
    {
        return $workgroup->update($data);
    }
}
