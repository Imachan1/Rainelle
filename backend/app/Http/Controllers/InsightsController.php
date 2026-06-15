<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InsightsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'range' => ['sometimes', Rule::in(['last_7_days', 'last_30_days', 'this_month', 'all_time'])],
        ]);

        $entries = auth()->user()
            ->moodEntries()
            ->tap(fn (Builder $query) => $this->applyDateRange($query, $validated['range'] ?? 'all_time'))
            ->get();
        $weatherEntries = $entries->filter(fn ($entry) => filled($entry->weather_condition));
        $streaks = $this->calculateMoodStreaks($entries->pluck('date'));

        return response()->json([
            'total_entries' => $entries->count(),
            'average_mood' => round((float) $entries->avg('mood_score'), 1),
            'best_mood' => $entries->max('mood_score'),
            'worst_mood' => $entries->min('mood_score'),
            'current_streak' => $streaks['current_streak'],
            'longest_streak' => $streaks['longest_streak'],
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

    private function applyDateRange(Builder $query, string $range): void
    {
        $startDate = match ($range) {
            'last_7_days' => now()->subDays(6)->toDateString(),
            'last_30_days' => now()->subDays(29)->toDateString(),
            'this_month' => now()->startOfMonth()->toDateString(),
            default => null,
        };

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
    }
}
