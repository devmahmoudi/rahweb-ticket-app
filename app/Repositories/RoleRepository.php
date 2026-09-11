<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function all(array $columns = ['*']): Collection
    {
        return Role::all($columns);
    }

    public function paginate(int $perPage = 10)
    {
        return Role::paginate($perPage);
    }

    public function store(array $data):Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data):bool
    {
        return $role->update($data);
    }

    public function delete(Role $role):bool
    {
        return $role->delete();
    }
}
