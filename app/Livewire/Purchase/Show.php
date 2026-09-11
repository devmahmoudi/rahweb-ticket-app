<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use Livewire\Component;

class Show extends Component
{
    public Purchase $purchase;

    public function render()
    {
        return view('livewire.pages.purchase.show');
    }
}
