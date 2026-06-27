<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class StoreFutureLetterRequest extends FormRequest
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
            'recipient_label' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:5000'],
            'unlock_at' => ['required', 'date', 'after:now'],
            'bg_color' => ['sometimes', 'nullable', 'string', 'max:20'],
            'text_color' => ['sometimes', 'nullable', 'string', 'max:20'],
        ];
    }
}
