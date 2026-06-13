<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'mood_entries_count' => auth()->user()->moodEntries()->count(),
            'latest_entry' => auth()->user()->moodEntries()->latest('entry_date')->first(),
        ]);
    }
}
