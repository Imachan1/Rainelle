<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMoodEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entry_date' => ['required', 'date'],
            'mood' => ['required', 'string', 'max:50'],
            'intensity' => ['required', 'integer', 'min:1', 'max:10'],
            'emotions' => ['nullable', 'array'],
            'emotions.*' => ['string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'weather_summary' => ['nullable', 'string', 'max:120'],
            'temperature' => ['nullable', 'numeric', 'between:-100,100'],
            'weather_data' => ['nullable', 'array'],
        ];
    }
}
