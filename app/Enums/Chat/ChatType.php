<?php

namespace App\Enums\Chat;

enum ChatType: string
{
    case PV = 'pv';

    case GROUP = 'group';

    case CHANNEL = 'channel';
}
