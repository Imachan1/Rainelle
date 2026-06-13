<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoodEntryRequest;
use App\Http\Requests\UpdateMoodEntryRequest;
use App\Models\MoodEntry;

class MoodEntryController extends Controller
{
    public function index()
    {
        return auth()->user()
            ->moodEntries()
            ->latest('entry_date')
            ->get();
    }

    public function store(StoreMoodEntryRequest $request)
    {
        $entry = $request->user()->moodEntries()->create($request->validated());

        return response()->json($entry, 201);
    }

    public function show(MoodEntry $moodEntry)
    {
        $this->authorizeEntry($moodEntry);

        return $moodEntry;
    }

    public function update(UpdateMoodEntryRequest $request, MoodEntry $moodEntry)
    {
        $this->authorizeEntry($moodEntry);

        $moodEntry->update($request->validated());

        return $moodEntry;
    }

    public function destroy(MoodEntry $moodEntry)
    {
        $this->authorizeEntry($moodEntry);

        $moodEntry->delete();

        return response()->noContent();
    }

    private function authorizeEntry(MoodEntry $moodEntry): void
    {
        abort_unless($moodEntry->user_id === auth()->id(), 403);
    }
}
