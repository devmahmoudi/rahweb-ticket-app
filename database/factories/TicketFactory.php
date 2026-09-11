<?php

namespace Database\Factories;

use App\Enums\Ticket\TicketStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\Workgroup;
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
            'customer_id' => Customer::factory(),
            'status' => $this->faker->randomElement(array_values(TicketStatus::cases()))
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
                'status' => TicketStatus::PENDING->value,
            ];
        });
    }

    /**
     * Indicate that the ticket is closed.
     */
    public function closed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TicketStatus::CLOSED->value,
            ];
        });
    }

    /**
     * Indicate that the ticket is waiting for accept with user.
     */
    public function waiting(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TicketStatus::WAITING->value,
            ];
        });
    }
}
