<?php

namespace App\Livewire\Meeting;

use App\Models\Meeting;
use Livewire\Component;

class Show extends Component
{
    public Meeting $meeting;

    public function mount()
    {
        $this->authorize('view', $this->meeting);
    }

    public function render()
    {
        return view('livewire.pages.meeting.show');
    }
}
