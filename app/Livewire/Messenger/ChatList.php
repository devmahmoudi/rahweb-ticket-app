<?php

namespace App\Livewire\Messenger;

use Illuminate\Database\Eloquent\Collection;

class ChatList extends Component
{
    public Collection $chats;

    public string $search;

    public function updatedSearch()
    {
        $this->chats = \App\Models\Chat::where('name', 'LIKE', "%$this->search%")->get();
    }


    public function mount()
    {
        $this->chats = \App\Models\Chat::all();

        if(isset($this->chat))
            $this->loadMessages($this->chat);
    }

    public function render()
    {
        return view('livewire.pages.messenger.chat-list');
    }
}
