<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateDailyCardTipRequest extends FormRequest
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
            'text_ar' => ['sometimes', 'required', 'string', 'max:5000'],
            'text_en' => ['sometimes', 'required', 'string', 'max:5000'],
            'emoji' => ['sometimes', 'nullable', 'string', 'max:16'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
