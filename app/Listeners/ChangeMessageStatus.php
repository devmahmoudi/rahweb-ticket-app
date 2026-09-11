<?php

namespace App\Listeners;

use App\Enums\Message\MessageStatus;
use App\Events\SeenMessage;
use App\Repositories\Message\MessageRepository;

class ChangeMessageStatus
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public MessageRepository $repository
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SeenMessage $event): void
    {
        $message = $this->repository->find($event->messageId);

        $this->repository->update($message, ['status' => MessageStatus::SEEN->value]);
    }
}
