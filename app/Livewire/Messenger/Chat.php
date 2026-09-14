<?php

namespace App\Livewire\Messenger;

use App\Models\Chat as ChatModel;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public ?ChatModel $chat;

    public array $chatsOnlineStatus;

    #[On('open-chat')]
    public function open(int $chatId)
    {
        $this->chat = Chat::find($chatId);
    }

    #[On('notify-online-status')]
    public function rememberChatOnlineStatus($chatId, $isOnline)
    {
        $this->chatsOnlineStatus[$chatId] = $isOnline;
    }

    public function render()
    {
        return view('livewire.pages.messenger.chat');
    }
}
