<?php

namespace App\Http\Requests\Companion;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateCompanionSuggestionRequest extends FormRequest
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
            'text_ar' => ['sometimes', 'required', 'string', 'max:255'],
            'text_en' => ['sometimes', 'required', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
