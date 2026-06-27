<?php

namespace App\Http\Requests\Mood;

use Illuminate\Foundation\Http\FormRequest;

class StoreMoodCheckinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'mood_key' => ['required', 'string', 'max:64'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
