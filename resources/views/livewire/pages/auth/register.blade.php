<?php

use App\Enums\User\UserType;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['type'] = UserType::CUSTOMER->value;

        $mailConfigured = config('mail.default') !== 'log';

        $user = User::create($validated);

        if (! $mailConfigured) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="card">
    <div class="card-body">
        <div>
            <div>
                <label for="name">نام*:</label>
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                              autofocus autocomplete="name"/>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <div class="mt-4">
                <label for="email">ایمیل*:</label>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                              autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="mt-4">
                <label for="password">کلمه عبور*:</label>

                <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <div class="mt-4">
                <label for="password_confirmation">تکرار کلمه عبور*:</label>

                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>

            <div class="d-flex justify-content-center flex-column">
                <button class="btn btn-success my-3" type="button" wire:click="register()">ثبت نام</button>
                <p class="text-center">
                    ثبت نام کرده اید؟
                    <a href="{{ route('login') }}" class="underline">ورود</a>
                </p>
            </div>
        </div>
    </div>
</div>
