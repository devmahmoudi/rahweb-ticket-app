<?php

namespace App\Livewire\Messenger;

use App\Models\Chat as ChatModel;
use App\Repositories\Message\MessageRepository;
use Livewire\Attributes\On;

class ChatListItem extends Component
{
    public bool $isOnline = false;

    public ChatModel $chat;

    public string $lastMessage;

    public int $newMessagesCount = 0;

    private MessageRepository $messageRepository;

    protected function getListeners()
    {
        return [
            'echo-presence:' . $this->getChatSocketChannelName($this->chat) . ',here' => 'checkContactOnline',
            'echo-presence:' . $this->getChatSocketChannelName($this->chat) . ',joining' => 'contactWentOnline',
            'echo-presence:' . $this->getChatSocketChannelName($this->chat) . ',leaving' => 'contactWentOffline',
            'echo-presence:' . $this->getChatSocketChannelName($this->chat) . ',MessageCreated' => 'newMessageReceived',
            'echo-presence:' . $this->getChatSocketChannelName($this->chat) . ',SeenMessage' => 'contactSeenMessage',
        ];
    }

    public function checkContactOnline($event)
    {
        $this->isOnline = (bool)(count($event) > 1);

        $this->notifyOnlineStatus($this->chat->id, $this->isOnline);
    }

    public function contactWentOffline($event)
    {
        $this->isOnline = false;

        $this->notifyOnlineStatus($this->chat->id, $this->isOnline);
    }

    public function contactWentOnline($event)
    {
        $this->isOnline = true;

        $this->notifyOnlineStatus($this->chat->id, $this->isOnline);
    }

    #[On('notify-new-message-sent')]
    public function updateLastMessage($chatId, $messageId, $body)
    {
        if($chatId == $this->chat->id)
            $this->lastMessage = $body;
    }

    public function newMessageReceived($event)
    {
        $repository = app()->makeWith(MessageRepository::class, ['chat', $this->chat]);

        $this->lastMessage = $repository->find($event['id'])->body;

        $this->incrementUnseenMessages();

        $this->notifyNewMessage($this->chat->id, $event['id']);
    }

    public function incrementUnseenMessages()
    {
        $this->newMessagesCount++;
    }

    #[On("notify-i-seen-message")]
    public function decrementUnseenMessages()
    {
        if($this->newMessagesCount > 0)
            $this->newMessagesCount--;
    }

    public function contactSeenMessage($event)
    {
        $this->notifyContactSeenMessage($this->chat->id, $event['id']);
    }

    public function open(ChatModel $chat)
    {
        $this->dispatch('open-chat', $chat->id);
    }

    public function mount()
    {
        $this->lastMessage = $this->chat->messages()->latest()->first()?->body;
    }

    public function render()
    {
        return view('livewire.pages.messenger.chat-list-item');
    }
}
