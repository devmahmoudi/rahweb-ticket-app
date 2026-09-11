<?php

namespace Database\Factories;

use App\Enums\Customer\CustomerType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nation_code' => rand(1111111111, 9999999999),
            'phone' => $this->faker->phoneNumber,
            'tel' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'type' => CustomerType::REAL->value
        ];
    }

    /**
     * Indicate that the user is legal type.
     */
    public function legal(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => CustomerType::LEGAL->value,
                'company_name' => $this->faker->company,
                'economic_code' => rand(111111, 999999),
            ];
        });
    }

    /**
     * Indicate that the user is real type.
     */
    public function real(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => CustomerType::REAL->value,
            ];
        });
    }
}
