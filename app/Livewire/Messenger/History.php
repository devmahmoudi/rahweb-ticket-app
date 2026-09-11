<?php

namespace App\Livewire\Messenger;

use App\Enums\Message\MessageStatus;
use App\Repositories\Message\MessageRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;

class History extends Component
{
    #[Reactive]
    public \App\Models\Chat $chat;

    public Collection $messages;

    #[Reactive]
    public bool $isContactOnline;

    #[On('notify-online-status')]
    public function changeOnlineStatus($chatId, $isOnline)
    {
        if($chatId == $this->chat->id)
            $this->isContactOnline = $isOnline;
    }

    public function groupByMessages($messages)
    {
        return $messages->groupBy(function ($message) use ($messages) {
            $changeUserAt = $messages
                ->where('id', '>' , $message->id)
                ->where('user_id', '!=' , $message->user_id)
                ->first();

            return $message->created_at->format('Y-m-d H:i') . '_' . $message->user_id . ($changeUserAt ? '_before_message_' . $changeUserAt->id : '');
        })->values();
    }

    #[On('notify-contact-seen-message')]
    public function changeMessageStatus($chatId, $messageId)
    {
        if($this->chat->id == $chatId)
            $this->messages->where('id', $messageId)->first()->status = MessageStatus::SEEN->value;
    }

    #[On('i-seen-message')]
    public function iSeenMessage($messageId)
    {
        $this->broadcastISeenMessage($this->chat->id, $messageId);
    }

    #[On('notify-new-message-sent')]
    #[On('notify-new-message')]
    public function pushSentMessage($chatId, $messageId)
    {
        if($this->chat->id == $chatId){
            $repository = app()->makeWith(MessageRepository::class, ['chat' => $this->chat]);

            $this->messages->push($repository->find($messageId));

            $this->dispatch('MessagesListUpdated');
        }
    }

    public function render()
    {
        $this->messages = $this->chat->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.pages.messenger.history')
            ->with('messages', $this->messages);
    }
}
