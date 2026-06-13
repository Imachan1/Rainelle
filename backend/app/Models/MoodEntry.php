<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'mood',
        'intensity',
        'emotions',
        'notes',
        'weather_summary',
        'temperature',
        'weather_data',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'emotions' => 'array',
            'weather_data' => 'array',
            'temperature' => 'decimal:1',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
