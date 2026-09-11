<?php

namespace App\Enums\Chat;

enum ChatUserConnectionStatus: string
{
    case CONNECTED = 'connected';

    case BLOCKED = 'blocked';
}
