<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workgroup;
use App\TicketStateManagement\TicketState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->realText,
            'workgroup_id' => Workgroup::factory(),
            'recipient_id' => User::factory(),
            'user_id' => User::factory()->customer(),
            'status' => $this->faker->randomElement(array_values(TicketState::cases()))
        ];
    }

    /**
     * Indicate the ticket recipient id
     */
    public function operator(User $user): Factory
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'recipient_id' => $user->id,
            ];
        });
    }

    /**
     * Indicate that the ticket is pending.
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TicketState::PENDING->value,
            ];
        });
    }

}
