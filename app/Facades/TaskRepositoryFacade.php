<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TaskRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'task-repository';
    }
}
