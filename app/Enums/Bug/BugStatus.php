<?php

namespace App\Enums\Bug;

enum BugStatus: string
{
    case PENDING = 'بر طرف نشده';

    case FIXED = 'بر طرف شده';
}
