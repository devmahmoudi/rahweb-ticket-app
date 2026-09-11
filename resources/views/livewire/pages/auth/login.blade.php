<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>


<div class="card">
    <div class="card-body">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <form wire:submit="login">
            <!-- Email Address -->
            <div>
                <label for="email">ایمیل :</label>
                <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                              required autofocus autocomplete="username"/>
                <x-input-error :messages="$errors->get('form.email')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password">کلمه عبور :</label>
                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="current-password"/>

                <x-input-error :messages="$errors->get('form.password')" class="mt-2"/>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">مرا به خاطر بسپار</span>
                </label>
            </div>

            <div class="d-flex justify-content-center flex-column">
                <button wire:submit="login()" class="btn btn-primary my-2">ورود</button>
                @if (Route::has('password.request'))
                    <a class="underline text-center text-sm m-2"
                       href="{{ route('password.request') }}" >
                        کلمه عبور خود را فراموش کرده اید ؟
                    </a>
                @endif
                <p class="text-center">
                    ثبت نام نکرده اید؟
                    <a href="{{ route('register') }}"  class="underline">ثبت نام</a>
                </p>
            </div>
        </form>
    </div>
</div>

{{--<div>--}}
{{--    <a href="/" >--}}
{{--        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />--}}
{{--    </a>--}}
{{--</div>--}}
