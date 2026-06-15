<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $entries = auth()->user()->moodEntries();
        $entryDates = (clone $entries)->pluck('date');
        $streaks = $this->calculateMoodStreaks($entryDates);

        return response()->json([
            'total_entries' => (clone $entries)->count(),
            'average_mood' => round((float) (clone $entries)->avg('mood_score'), 1),
            'latest_entries' => (clone $entries)->latest('date')->limit(3)->get(),
            'today_entry' => (clone $entries)->whereDate('date', now()->toDateString())->first(),
            'current_streak' => $streaks['current_streak'],
            'longest_streak' => $streaks['longest_streak'],
        ]);
    }
}
