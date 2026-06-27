<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class StoreGratitudeNoteRequest extends FormRequest
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
            'text' => ['required', 'string', 'max:500'],
            'color' => ['sometimes', 'nullable', 'string', 'max:20'],
            'rotation' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
        ];
    }
}
