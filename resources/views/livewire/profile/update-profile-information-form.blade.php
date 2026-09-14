<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $name = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->fill($validated);
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }
};
?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            مشخصات پروفایل
        </h2>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <!-- Name -->
        <div>
            <label for="name">نام*:</label>
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email">ایمیل:</label>
            <div class="block mt-1 w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-gray-700">
                {{ auth()->user()->email }}
            </div>
        </div>

        <div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>ذخیره</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                ذخیره شد
            </x-action-message>
        </div>
    </form>
</section>