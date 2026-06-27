<?php

namespace App\Http\Requests\Help;

use Illuminate\Foundation\Http\FormRequest;

class StoreHelpAskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:5000'],
            'circle_id' => ['nullable', 'integer', 'exists:circles,id'],
            'is_anonymous' => ['sometimes', 'boolean'],
        ];
    }
}
