<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $nation_code = '';
    public string $phone = '';
    public string $tel = '';
    public string $address = '';
    public string $type = '';
    public string $company_name = '';
    public string $economic_code = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount()
    {
            $this->type = \App\Enums\Customer\CustomerType::REAL->value;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'nation_code' => ['required', 'string', 'min:10', 'max:10', \Illuminate\Validation\Rule::unique(\App\Models\Customer::class)],
            'phone' => ['required', 'string', 'min:11', 'max:11'],
            'tel' => ['nullable', 'string', 'min:11', 'max:11'],
            'address' => ['nullable', 'string', 'max:3000'],
            'type' => ['required', 'string', \Illuminate\Validation\Rule::in(array_values(\App\Enums\Customer\CustomerType::cases()))],
            'company_name' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::requiredIf(fn() => $this->type == \App\Enums\Customer\CustomerType::LEGAL->value)],
            'economic_code' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::requiredIf(fn() => $this->type == \App\Enums\Customer\CustomerType::LEGAL->value)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $customer = Customer::create([
            'nation_code' => $this->nation_code,
            'phone' => $this->phone,
            'tel' => $this->tel,
            'address' => $this->address,
            'type' => $this->type,
            'company_name' => $this->company_name,
            'economic_code' => $this->economic_code,
        ]);

        $validated['type'] = \App\Enums\User\UserType::CUSTOMER->value;

        event(new Registered($user = $customer->user()->create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="card">
    <div class="card-body">
        <div>
            <!-- Name -->
            <div>
                <label for="name">نام*:</label>
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                              autofocus autocomplete="name"/>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <!-- Nation code -->
            <div class="mt-4">
                <label for="nation_code">کد ملی*:</label>
                <x-text-input wire:model="nation_code" id="nation_code" class="block mt-2 w-full" type="text"
                              name="nation_code" required autofocus autocomplete="nation_code"/>
                <x-input-error :messages="$errors->get('nation_code')" class="mt-2"/>
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <label for="phone">شماره تلفن همراه*:</label>
                <x-text-input wire:model="phone" id="phone" class="block mt-2 w-full" type="text" name="phone" required
                              autofocus autocomplete="phone"/>
                <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
            </div>

            <!-- Type -->
            <div class="mt-4">
                <label for="type">نوع*:</label>
                <select wire:model.live="type" id="type" class="form-control px-5">
                    <option value="{{ \App\Enums\Customer\CustomerType::REAL->value }}" selected>حقیقی</option>
                    <option value="{{ \App\Enums\Customer\CustomerType::LEGAL->value }}">حقوقی</option>
                </select>
                <x-input-error :messages="$errors->get('type')" class="mt-2"/>
            </div>

            <!-- Company name -->
            @if($type == \App\Enums\Customer\CustomerType::LEGAL->value)
                <div class="mt-4">
                    <label for="company_name">نام شرکت*:</label>
                    <x-text-input wire:model="company_name" id="company_name" class="block mt-2 w-full" type="text"
                                  name="company_name" autofocus autocomplete="company_name"/>
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2"/>
                </div>
            @endif

        <!-- Economic code -->
            @if($type == \App\Enums\Customer\CustomerType::LEGAL->value)
                <div class="mt-4">
                    <label for="economic_code">کد اقتصادی*:</label>
                    <x-text-input wire:model="economic_code" id="economic_code" class="block mt-2 w-full" type="text"
                                  name="economic_code" autofocus autocomplete="economic_code"/>
                    <x-input-error :messages="$errors->get('economic_code')" class="mt-2"/>
                </div>
        @endif


        <!-- Tel -->
            <div class="mt-4">
                <label for="tel">شماره تلفن:</label>
                <x-text-input wire:model="tel" id="tel" class="block mt-2 w-full" type="text" name="tel" autofocus
                              autocomplete="tel"/>
                <x-input-error :messages="$errors->get('tel')" class="mt-2"/>
            </div>

            <!-- Address -->
            <div class="mt-4">
                <label for="address">آدرس :</label>
                <x-text-input wire:model="address" id="address" class="block mt-2 w-full" type="text" name="address"
                              autofocus autocomplete="address"/>
                <x-input-error :messages="$errors->get('address')" class="mt-2"/>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <label for="name">ایمیل*:</label>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                              autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="name">کلمه عبور*:</label>

                <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="name">تکرار کلمه عبور*:</label>

                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>

            <div class="d-flex justify-content-center flex-column">
                <button class="btn btn-success my-3" type="button" wire:click="register()">ثبت نام</button>
                <p class="text-center">
                    ثبت نام کرده اید؟
                    <a href="{{ route('login') }}"  class="underline">ورود</a>
                </p>
            </div>
        </div>
    </div>
</div>
