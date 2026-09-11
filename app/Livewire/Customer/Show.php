<?php

namespace App\Livewire\Customer;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public function mount()
    {
        $this->authorize('view', $this->user);
    }

    public function render()
    {
        return view('livewire.pages.customer.show');
    }
}
