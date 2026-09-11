<?php

namespace App\Enums\Message;

enum MessageStatus: string
{
    case SENT = 'sent';

    case SEEN = 'seen';
}
