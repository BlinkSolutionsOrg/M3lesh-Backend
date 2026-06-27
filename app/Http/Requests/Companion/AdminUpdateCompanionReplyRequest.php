<?php

namespace App\Http\Requests\Companion;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateCompanionReplyRequest extends FormRequest
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
            'text_ar' => ['sometimes', 'required', 'string', 'max:2000'],
            'text_en' => ['sometimes', 'required', 'string', 'max:2000'],
            'is_greeting' => ['sometimes', 'boolean'],
            'weight' => ['sometimes', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
