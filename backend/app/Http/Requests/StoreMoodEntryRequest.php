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
            'date' => ['required', 'date'],
            'mood_score' => ['required', 'integer', 'min:1', 'max:10'],
            'emotion' => ['required', 'string', 'max:80'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
