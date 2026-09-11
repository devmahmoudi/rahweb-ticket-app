<?php

namespace App\Enums\Task;

enum TaskType: string
{
    case SUBMIT = 'ارسالی';

    case RECEIVED = 'دریافتی';
}
