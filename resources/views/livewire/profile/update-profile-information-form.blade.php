<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $name = '';
    public ?string $nation_code = '';
    public ?string $phone = '';
    public ?string $tel = '';
    public ?string $address = '';
    public ?string $customerType = '';
    public ?string $company_name = '';
    public ?string $economic_code = '';
    public ?string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;

        if ($user->type == \App\Enums\User\UserType::CUSTOMER->value) {
            $this->phone = $user->customer->phone;
            $this->nation_code = $user->customer->nation_code;
            $this->tel = $user->customer->tel;
            $this->address = $user->customer->address;
            $this->customerType = $user->customer->type;
            $this->company_name = $user->customer->company_name;
            $this->economic_code = $user->customer->economic_code;
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ];

        if ($user->type == \App\Enums\User\UserType::CUSTOMER->value) {
            $rules = array_merge($rules, [
                'nation_code' => ['required', 'string', 'min:10', 'max:10', \Illuminate\Validation\Rule::unique(\App\Models\Customer::class)->ignore($user->customer->id)],
                'phone' => ['required', 'string', 'min:11', 'max:11'],
                'tel' => ['nullable', 'string', 'min:11', 'max:11'],
                'address' => ['nullable', 'string', 'max:3000'],
                'customerType' => ['required', 'string', \Illuminate\Validation\Rule::in([\App\Enums\Customer\CustomerType::REAL->value, \App\Enums\Customer\CustomerType::LEGAL->value])],
                'company_name' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::requiredIf(fn() => $this->customerType == \App\Enums\Customer\CustomerType::LEGAL->value)],
                'economic_code' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::requiredIf(fn() => $this->customerType == \App\Enums\Customer\CustomerType::LEGAL->value)],
            ]);
        }

        $validated = $this->validate($rules);

        if ($user->type == \App\Enums\User\UserType::CUSTOMER->value)
            $user->customer->update([
                'nation_code' => $this->nation_code,
                'phone' => $this->phone,
                'tel' => $this->tel,
                'address' => $this->address,
                'type' => $this->customerType,
                'company_name' => $this->company_name,
                'economic_code' => $this->economic_code,
            ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

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
                          autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
        </div>

        @if(auth()->user()->type == \App\Enums\User\UserType::CUSTOMER->value)
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
                    <select wire:model.live="customerType" id="type" class="form-control px-5">
                        <option value="{{ \App\Enums\Customer\CustomerType::REAL->value }}" selected>حقیقی</option>
                        <option value="{{ \App\Enums\Customer\CustomerType::LEGAL->value }}">حقوقی</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2"/>
                </div>

                <!-- Company name -->
                @if($customerType == \App\Enums\Customer\CustomerType::LEGAL->value)
                    <div class="mt-4">
                        <label for="company_name">نام شرکت*:</label>
                        <x-text-input wire:model="company_name" id="company_name" class="block mt-2 w-full" type="text"
                                      name="company_name" autofocus autocomplete="company_name"/>
                        <x-input-error :messages="$errors->get('company_name')" class="mt-2"/>
                    </div>
                @endif

            <!-- Economic code -->
                @if($customerType == \App\Enums\Customer\CustomerType::LEGAL->value)
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
        @endif

        <!-- Email Address -->
        <div class="mt-4">
            <label for="name">ایمیل*:</label>
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                          autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        ایمیل شما احراز نشده است.

                        <button wire:click.prevent="sendVerification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            برای ارسال لینک فعال سازی کلیک کنید.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            لینک فعال سازی به آدرس ایمیل ثبت شده ارسال شد.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>ذخیره</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                ذخیره شد
            </x-action-message>
        </div>
    </form>
</section>
