<?php

namespace App\Listeners;

use App\Enums\Message\MessageStatus;
use App\Events\SeenMessage;
use App\Models\Message;

class ChangeMessageStatus
{
    /**
     * Create the event listener.
     */
    public function __construct(
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SeenMessage $event): void
    {
        Message::find($event->messageId)->update(['status' => MessageStatus::SEEN->value]);
    }
}
