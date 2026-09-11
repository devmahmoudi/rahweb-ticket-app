<?php

namespace Database\Factories;

use App\Enums\Chat\ChatType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'link' => $this->faker->unique()->uuid,
            'type' => $this->faker->randomElement(array_values(ChatType::cases())),
        ];
    }
}
