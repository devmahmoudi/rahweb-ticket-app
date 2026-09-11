<?php

namespace App\Livewire\Customer;

use App\Models\User;
use App\Repositories\UserRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = app()->make(UserRepository::class);
    }

    public function delete(User $user)
    {
        $this->authorize('delete', $user);

        $this->userRepository->delete($user);
    }

    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    public function render(UserRepository $repository)
    {
        return view('livewire.pages.customer.index')
            ->with('customers', $repository->allCustomers(true));
    }
}
