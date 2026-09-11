<?php

namespace App\Enums\Task;

enum TaskStatus: string
{
    case SENT = 'مشاهده نشده';

    case PENDING = 'در حال رسیدگی';

    case CLOSED = 'بسته شده';
}
