<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'mood_score',
        'emotion',
        'note',
        'temperature',
        'humidity',
        'pressure',
        'wind_speed',
        'weather_condition',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'mood_score' => 'integer',
            'temperature' => 'decimal:2',
            'humidity' => 'integer',
            'pressure' => 'integer',
            'wind_speed' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
