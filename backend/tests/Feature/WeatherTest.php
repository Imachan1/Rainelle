<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_current_weather_by_coordinates(): void
    {
        config(['services.openweather.key' => 'test-key']);

        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'main' => [
                    'temp' => 21.4,
                    'humidity' => 58,
                    'pressure' => 1013,
                ],
                'wind' => [
                    'speed' => 4.7,
                ],
                'weather' => [
                    ['description' => 'clear sky'],
                ],
            ]),
        ]);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/weather/current?lat=52.23&lon=21.01')
            ->assertOk()
            ->assertJsonPath('data.temperature', 21.4)
            ->assertJsonPath('data.humidity', 58)
            ->assertJsonPath('data.pressure', 1013)
            ->assertJsonPath('data.wind_speed', 4.7)
            ->assertJsonPath('data.weather_condition', 'clear sky');
    }

    public function test_coordinates_are_required_for_weather_lookup(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/weather/current')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['lat', 'lon']);
    }

    public function test_weather_api_key_must_be_configured(): void
    {
        config(['services.openweather.key' => null]);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/weather/current?lat=52.23&lon=21.01')
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Weather API key is not configured.');
    }
}
