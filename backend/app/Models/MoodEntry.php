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
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'mood_score' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
