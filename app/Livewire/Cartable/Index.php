<?php

namespace App\Livewire\Cartable;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Index extends Component
{
    public function mount()
    {
        if(!Gate::allows('cartable'))
            abort(403, 'You are not an operator so you have not cartable !');
    }

    public function render()
    {
        return view('livewire.pages.cartable.index');
    }
}
