<?php

namespace App\Enums\Permission;

enum BasicPermission: string
{
    case CREATE = 'create';

    case READ = 'read';

    case UPDATE = 'update';

    case DELETE = 'delete';
}
