<?php

namespace Database\Factories;

use App\Enums\User\UserType;
use App\Models\Customer;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'type' => UserType::ADMIN->value,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => Role::factory(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate customer type for new user.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserType::CUSTOMER->value,
            'customer_id' => Customer::factory(),
        ]);
    }

    /**
     * Indicate operator type for new user.
     */
    public function operator(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserType::OPERATOR->value,
        ]);
    }

    /**
     * Indicate admin type for new user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserType::ADMIN->value,
        ]);
    }
}
