<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;

abstract class Controller
{
    protected function calculateMoodStreaks($entryDates): array
    {
        $dates = $entryDates
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->sort()
            ->values();

        $longestStreak = 0;
        $streak = 0;
        $previousDate = null;

        foreach ($dates as $date) {
            $streak = $previousDate && Carbon::parse($previousDate)->addDay()->toDateString() === $date
                ? $streak + 1
                : 1;

            $longestStreak = max($longestStreak, $streak);
            $previousDate = $date;
        }

        $currentStreak = 0;
        $dateSet = $dates->flip();
        $currentDate = now()->startOfDay();

        while ($dateSet->has($currentDate->toDateString())) {
            $currentStreak++;
            $currentDate->subDay();
        }

        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
        ];
    }
}
