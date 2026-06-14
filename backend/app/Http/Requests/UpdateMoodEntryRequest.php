<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMoodEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['sometimes', 'date'],
            'mood_score' => ['sometimes', 'integer', 'min:1', 'max:10'],
            'emotion' => ['sometimes', 'string', 'max:80'],
            'note' => ['nullable', 'string', 'max:2000'],
            'temperature' => ['nullable', 'numeric', 'between:-100,100'],
            'humidity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'pressure' => ['nullable', 'integer', 'min:800', 'max:1200'],
            'wind_speed' => ['nullable', 'numeric', 'min:0', 'max:150'],
            'weather_condition' => ['nullable', 'string', 'max:80'],
        ];
    }
}
