<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository
{
    public function all(array $columns = ['*']): Collection
    {
        return Permission::all($columns);
    }
}
