<?php

namespace Tests\Feature;

use App\Models\MoodEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InsightsTest extends TestCase
{
    use RefreshDatabase;

    public function test_insights_returns_empty_statistics(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/insights')
            ->assertOk()
            ->assertJsonPath('total_entries', 0)
            ->assertJsonPath('average_mood', 0)
            ->assertJsonPath('best_mood', null)
            ->assertJsonPath('worst_mood', null)
            ->assertJsonPath('current_streak', 0)
            ->assertJsonPath('longest_streak', 0)
            ->assertJsonPath('most_common_emotion', null)
            ->assertJsonPath('average_temperature', 0)
            ->assertJsonPath('mood_by_weather_condition', []);
    }

    public function test_insights_returns_mood_and_weather_statistics(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        MoodEntry::factory()->for($user)->create([
            'mood_score' => 8,
            'emotion' => 'Calm',
            'temperature' => 20,
            'weather_condition' => 'Clear',
        ]);
        MoodEntry::factory()->for($user)->create([
            'mood_score' => 6,
            'emotion' => 'Calm',
            'temperature' => 22,
            'weather_condition' => 'Clear',
        ]);
        MoodEntry::factory()->for($user)->create([
            'mood_score' => 4,
            'emotion' => 'Tired',
            'temperature' => 10,
            'weather_condition' => 'Rain',
        ]);
        MoodEntry::factory()->for($user)->create([
            'mood_score' => 10,
            'emotion' => 'Happy',
        ]);
        MoodEntry::factory()->for($otherUser)->create([
            'mood_score' => 1,
            'emotion' => 'Sad',
            'temperature' => 0,
            'weather_condition' => 'Snow',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/insights')
            ->assertOk()
            ->assertJsonPath('total_entries', 4)
            ->assertJsonPath('average_mood', 7)
            ->assertJsonPath('best_mood', 10)
            ->assertJsonPath('worst_mood', 4)
            ->assertJsonPath('most_common_emotion', 'Calm')
            ->assertJsonPath('average_temperature', 17.3)
            ->assertJsonPath('mood_by_weather_condition.Clear', 7)
            ->assertJsonPath('mood_by_weather_condition.Rain', 4)
            ->assertJsonMissingPath('mood_by_weather_condition.Snow');
    }

    public function test_insights_can_be_filtered_by_date_range(): void
    {
        $user = User::factory()->create();

        MoodEntry::factory()->for($user)->create([
            'date' => now()->subDays(2)->toDateString(),
            'mood_score' => 8,
            'emotion' => 'calm',
            'temperature' => 20,
            'weather_condition' => 'Clear',
        ]);
        MoodEntry::factory()->for($user)->create([
            'date' => now()->subDays(20)->toDateString(),
            'mood_score' => 2,
            'emotion' => 'tired',
            'temperature' => 10,
            'weather_condition' => 'Rain',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/insights?range=last_7_days')
            ->assertOk()
            ->assertJsonPath('total_entries', 1)
            ->assertJsonPath('average_mood', 8)
            ->assertJsonPath('mood_by_weather_condition.Clear', 8)
            ->assertJsonMissingPath('mood_by_weather_condition.Rain');
    }

    public function test_insights_returns_mood_streaks(): void
    {
        $user = User::factory()->create();

        MoodEntry::factory()->for($user)->create(['date' => now()->toDateString()]);
        MoodEntry::factory()->for($user)->create(['date' => now()->subDay()->toDateString()]);
        MoodEntry::factory()->for($user)->create(['date' => now()->subDays(3)->toDateString()]);
        MoodEntry::factory()->for($user)->create(['date' => now()->subDays(4)->toDateString()]);
        MoodEntry::factory()->for($user)->create(['date' => now()->subDays(5)->toDateString()]);

        Sanctum::actingAs($user);

        $this->getJson('/api/insights')
            ->assertOk()
            ->assertJsonPath('current_streak', 2)
            ->assertJsonPath('longest_streak', 3);
    }
}
