<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreDailyCardTipRequest extends FormRequest
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
            'text_ar' => ['required', 'string', 'max:5000'],
            'text_en' => ['required', 'string', 'max:5000'],
            'emoji' => ['nullable', 'string', 'max:16'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
