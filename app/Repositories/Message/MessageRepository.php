<?php

namespace App\Repositories\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Ticket;
use App\View\Components\InitialTicketMessage;
use function app;

class MessageRepository
{
    use HasTicketMessageMethods, HasTaskMessageMethods;

    /**
     * Create a new class instance.
     */
    public function __construct(
        private Chat $chat
    )
    {}

    /**
     * @param array $data
     * @return Message|false
     */
    public function create(array $data):Message|false
    {
        return $this->chat->messages()->create($data);
    }

    public function update(Message $message, array $data):bool
    {
        return $message->update($data);
    }

    public function find(int $id):Message|null
    {
        return Message::find($id);
    }
}
