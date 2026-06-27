<?php

namespace App\Http\Requests\Help;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateHelpAskRequest extends FormRequest
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
            'circle_id' => ['sometimes', 'nullable', 'integer', 'exists:circles,id'],
            'title' => ['sometimes', 'required', 'string', 'max:500'],
            'body' => ['sometimes', 'required', 'string', 'max:5000'],
            'is_anonymous' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string', Rule::in(['open', 'closed'])],
        ];
    }
}
