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
        ];
    }
}
