<?php

namespace App\Http\Controllers;

class WeatherController extends Controller
{
    public function current()
    {
        return response()->json([
            'message' => 'Weather integration will be added later.',
            'data' => null,
        ]);
    }
}
