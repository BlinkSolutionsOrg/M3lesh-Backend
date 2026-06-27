<?php

namespace App\Http\Requests\Help;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHelpReplyRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in(['advice', 'experience'])],
            'body' => ['required', 'string', 'max:5000'],
            'is_anonymous' => ['sometimes', 'boolean'],
        ];
    }
}
