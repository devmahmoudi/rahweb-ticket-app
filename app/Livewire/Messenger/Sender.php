<?php

namespace App\Livewire\Messenger;

use App\Repositories\Message\MessageRepository;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;

class Sender extends Component
{
    private MessageRepository $repository;

    #[Reactive]
    public \App\Models\Chat $chat;

    #[Validate(['required', 'string'])]
    public string $body;

    public function send()
    {
        $this->validate();

        $repository = app()->makeWith(MessageRepository::class, ['chat' => $this->chat]);

        $message = $repository->create([
            'body' => $this->body,
            'user_id' => auth()->id()
        ]);

        if($message){
            $this->notifyNewMessageSent($this->chat->id, $message->id , $this->body);

            $this->reset('body');
        } else
            session()->now('toast-danger', 'مشکلی پیش آمده است !');
    }

    public function mount()
    {
        $this->repository = app()->makeWith(MessageRepository::class, ['chat' => $this->chat]);
    }

    public function render()
    {
        return view('livewire.pages.messenger.sender');
    }
}
