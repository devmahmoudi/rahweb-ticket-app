<?php

namespace Database\Factories;

use App\Enums\Bug\BugStatus;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
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
            'creator_id' => User::factory(),
            'status' => $this->faker->randomElement(array_values(BugStatus::cases()))
        ];
    }

    /**
     * Indicate that the bug is fixed.
     */
    public function fixed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BugStatus::FIXED->value,
            ];
        });
    }

    /**
     * Indicate that the bug is in pending situation.
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BugStatus::PENDING->value,
            ];
        });
    }
}
