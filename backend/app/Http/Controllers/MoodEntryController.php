<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoodEntryRequest;
use App\Http\Requests\UpdateMoodEntryRequest;
use App\Models\MoodEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MoodEntryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'range' => ['sometimes', Rule::in(['last_7_days', 'last_30_days', 'this_month', 'all_time'])],
        ]);

        $entries = auth()->user()
            ->moodEntries()
            ->tap(fn (Builder $query) => $this->applyDateRange($query, $validated['range'] ?? 'all_time'))
            ->latest('date')
            ->get();

        return response()->json(['data' => $entries]);
    }

    public function store(StoreMoodEntryRequest $request)
    {
        $entry = $request->user()->moodEntries()->create($request->validated());

        return response()->json(['data' => $entry], 201);
    }

    public function show(MoodEntry $moodEntry)
    {
        $this->authorizeEntry($moodEntry);

        return response()->json(['data' => $moodEntry]);
    }

    public function update(UpdateMoodEntryRequest $request, MoodEntry $moodEntry)
    {
        $this->authorizeEntry($moodEntry);

        $moodEntry->update($request->validated());

        return response()->json(['data' => $moodEntry]);
    }

    public function destroy(MoodEntry $moodEntry)
    {
        $this->authorizeEntry($moodEntry);

        $moodEntry->delete();

        return response()->json(['message' => 'Mood entry deleted.']);
    }

    private function authorizeEntry(MoodEntry $moodEntry): void
    {
        abort_unless($moodEntry->user_id === auth()->id(), 403);
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
