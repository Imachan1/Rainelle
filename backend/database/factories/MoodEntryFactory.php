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
            'date' => fake()->date(),
            'mood_score' => fake()->numberBetween(1, 10),
            'emotion' => fake()->randomElement(['calm', 'happy', 'tired', 'stressed']),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
