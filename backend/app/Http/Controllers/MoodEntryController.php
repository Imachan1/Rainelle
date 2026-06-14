<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoodEntryRequest;
use App\Http\Requests\UpdateMoodEntryRequest;
use App\Models\MoodEntry;

class MoodEntryController extends Controller
{
    public function index()
    {
        $entries = auth()->user()
            ->moodEntries()
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
}
