<?php

namespace Tests\Feature;

use App\Models\MoodEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MoodEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_mood_entry(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/mood-entries', [
            'date' => '2026-06-14',
            'mood_score' => 8,
            'emotion' => 'calm',
            'note' => 'A quiet day.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.date', '2026-06-14')
            ->assertJsonPath('data.mood_score', 8)
            ->assertJsonPath('data.emotion', 'calm');

        $this->assertDatabaseHas('mood_entries', [
            'date' => '2026-06-14',
            'mood_score' => 8,
            'emotion' => 'calm',
        ]);
    }

    public function test_user_can_list_only_their_mood_entries(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        MoodEntry::factory()->for($user)->create(['date' => '2026-06-14']);
        MoodEntry::factory()->for($otherUser)->create(['date' => '2026-06-15']);

        Sanctum::actingAs($user);

        $this->getJson('/api/mood-entries')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.date', '2026-06-14');
    }

    public function test_user_can_update_their_mood_entry(): void
    {
        $user = User::factory()->create();
        $entry = MoodEntry::factory()->for($user)->create([
            'mood_score' => 4,
            'emotion' => 'tired',
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/mood-entries/{$entry->id}", [
            'mood_score' => 7,
            'emotion' => 'focused',
            'note' => 'Felt better after lunch.',
        ])
            ->assertOk()
            ->assertJsonPath('data.mood_score', 7)
            ->assertJsonPath('data.emotion', 'focused');

        $this->assertDatabaseHas('mood_entries', [
            'id' => $entry->id,
            'mood_score' => 7,
            'emotion' => 'focused',
        ]);
    }

    public function test_user_can_delete_their_mood_entry(): void
    {
        $user = User::factory()->create();
        $entry = MoodEntry::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/mood-entries/{$entry->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Mood entry deleted.');

        $this->assertDatabaseMissing('mood_entries', [
            'id' => $entry->id,
        ]);
    }

    public function test_user_cannot_access_another_users_mood_entry(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $entry = MoodEntry::factory()->for(User::factory())->create();

        $this->getJson("/api/mood-entries/{$entry->id}")->assertForbidden();
        $this->putJson("/api/mood-entries/{$entry->id}", [
            'mood_score' => 9,
        ])->assertForbidden();
        $this->deleteJson("/api/mood-entries/{$entry->id}")->assertForbidden();
    }
}
