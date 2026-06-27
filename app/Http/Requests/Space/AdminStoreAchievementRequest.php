<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreAchievementRequest extends FormRequest
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
            'key' => ['required', 'string', 'max:255', 'unique:achievements,key'],
            'icon' => ['required', 'string', 'max:16'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'criterion_ar' => ['required', 'string', 'max:255'],
            'criterion_en' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
