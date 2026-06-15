<?php

namespace Tests\Feature;

use App\Models\MoodEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_empty_summary(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('total_entries', 0)
            ->assertJsonPath('average_mood', 0)
            ->assertJsonPath('latest_entries', [])
            ->assertJsonPath('today_entry', null)
            ->assertJsonPath('current_streak', 0)
            ->assertJsonPath('longest_streak', 0);
    }

    public function test_dashboard_returns_user_summary(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        MoodEntry::factory()->for($user)->create([
            'date' => now()->toDateString(),
            'mood_score' => 8,
            'emotion' => 'calm',
        ]);
        MoodEntry::factory()->for($user)->create([
            'date' => now()->subDay()->toDateString(),
            'mood_score' => 6,
            'emotion' => 'focused',
        ]);
        MoodEntry::factory()->for($user)->create([
            'date' => now()->subDays(2)->toDateString(),
            'mood_score' => 4,
            'emotion' => 'tired',
        ]);
        MoodEntry::factory()->for($user)->create([
            'date' => now()->subDays(3)->toDateString(),
            'mood_score' => 2,
            'emotion' => 'stressed',
        ]);
        MoodEntry::factory()->for($otherUser)->create([
            'date' => now()->toDateString(),
            'mood_score' => 10,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('total_entries', 4)
            ->assertJsonPath('average_mood', 5)
            ->assertJsonCount(3, 'latest_entries')
            ->assertJsonPath('latest_entries.0.emotion', 'calm')
            ->assertJsonPath('today_entry.emotion', 'calm')
            ->assertJsonPath('current_streak', 4)
            ->assertJsonPath('longest_streak', 4);
    }
}
