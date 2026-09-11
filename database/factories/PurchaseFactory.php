<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name,
            'sale_date' => $this->faker->date,
            'amount_paid' => rand(11111111, 9999999),
            'phone' => $this->faker->phoneNumber,
            'description' => $this->faker->realText,
            'creator_id' => User::factory(),
        ];
    }
}
