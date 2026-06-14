<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function current(Request $request)
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lon' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $apiKey = config('services.openweather.key');

        if (! $apiKey) {
            return response()->json([
                'message' => 'Weather API key is not configured.',
            ], 422);
        }

        $weather = Http::timeout(8)->get(config('services.openweather.url'), [
            'lat' => $data['lat'],
            'lon' => $data['lon'],
            'appid' => $apiKey,
            'units' => 'metric',
        ])->throw()->json();

        return response()->json([
            'data' => [
                'temperature' => data_get($weather, 'main.temp'),
                'humidity' => data_get($weather, 'main.humidity'),
                'pressure' => data_get($weather, 'main.pressure'),
                'wind_speed' => data_get($weather, 'wind.speed'),
                'weather_condition' => data_get($weather, 'weather.0.description'),
            ],
        ]);
    }
}
