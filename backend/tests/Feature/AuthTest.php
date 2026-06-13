<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Rainelle User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_user_can_login_and_fetch_profile(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $login = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $token = $login->json('token');

        $login
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('user.email', 'user@example.com');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('rainelle')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logged out.');

        $this->assertSame(0, PersonalAccessToken::count());
    }

    public function test_login_requires_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ])->assertUnprocessable();
    }
}
