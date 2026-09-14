<?php

namespace App\Livewire\Customer;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function __construct()
    {
    }

    public function delete(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
    }

    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    public function render()
    {
        return view('livewire.pages.customer.index')
            ->with('customers', User::customer()->paginate());
    }
}
