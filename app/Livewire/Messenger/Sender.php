<?php

namespace App\Livewire\Messenger;

use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;

class Sender extends Component
{
    #[Reactive]
    public \App\Models\Chat $chat;

    #[Validate(['required', 'string'])]
    public string $body;

    public function send()
    {
        $this->validate();


        $message = $this->chat->messages()->create([
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
    }

    public function render()
    {
        return view('livewire.pages.messenger.sender');
    }
}
