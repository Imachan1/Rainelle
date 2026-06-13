<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoodEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'entry_date' => fake()->date(),
            'mood' => fake()->randomElement(['calm', 'happy', 'tired', 'stressed']),
            'intensity' => fake()->numberBetween(1, 10),
            'emotions' => [],
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
