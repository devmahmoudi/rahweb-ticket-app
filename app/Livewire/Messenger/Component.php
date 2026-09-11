<?php

namespace App\Livewire\Messenger;

use App\Events\SeenMessage;

class Component extends \Livewire\Component
{
    public function getChatSocketChannelName(\App\Models\Chat $chat):string
    {
        return config('chat.channel-prefix') . $chat->id;
    }

    public function notifyOnlineStatus(int $chatId, bool $isOnline)
    {
        $this->dispatch('notify-online-status', chatId: $chatId, isOnline: $isOnline);
    }

    public function notifyNewMessage(int $chatId, int $messageId)
    {
        $this->dispatch('notify-new-message', chatId: $chatId, messageId: $messageId);
    }

    public function notifyContactSeenMessage(int $chatId, int $messageId)
    {
        $this->dispatch('notify-contact-seen-message', chatId: $chatId, messageId: $messageId);
    }

    public function notifyNewMessageSent(int $chatId, int $messageId, string $body)
    {
        $this->dispatch('notify-new-message-sent', chatId: $chatId, messageId: $messageId, body: $body);
    }

    public function broadcastISeenMessage(int $chatId, int $messageId)
    {
        broadcast(new SeenMessage($messageId, $this->chat->id))->toOthers();

        $this->dispatch('notify-i-seen-message', chatId: $chatId, messageId: $messageId);
    }
}
