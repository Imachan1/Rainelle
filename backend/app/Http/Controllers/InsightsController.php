<?php

namespace App\Http\Controllers;

class InsightsController extends Controller
{
    public function index()
    {
        $entries = auth()->user()->moodEntries()->get();
        $weatherEntries = $entries->filter(fn ($entry) => filled($entry->weather_condition));

        return response()->json([
            'total_entries' => $entries->count(),
            'average_mood' => round((float) $entries->avg('mood_score'), 1),
            'best_mood' => $entries->max('mood_score'),
            'worst_mood' => $entries->min('mood_score'),
            'most_common_emotion' => $entries
                ->groupBy('emotion')
                ->sortByDesc(fn ($group) => $group->count())
                ->keys()
                ->first(),
            'average_temperature' => round((float) $entries
                ->whereNotNull('temperature')
                ->avg('temperature'), 1),
            'mood_by_weather_condition' => $weatherEntries
                ->groupBy('weather_condition')
                ->map(fn ($group) => round((float) $group->avg('mood_score'), 1))
                ->sortKeys()
                ->all(),
        ]);
    }
}
