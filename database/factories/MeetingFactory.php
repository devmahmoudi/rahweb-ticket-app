<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::factory()->create();

        $userCustomer = User::factory()->for($customer)->create();

        return [
            'title' => $this->faker->name,
            'date' => $this->faker->date,
            'customer_id' => $customer->id,
            'text' => $this->faker->realText,
            'participants' => $this->faker->name,
            'creator_id' => User::factory(),
        ];
    }
}
