<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateAchievementRequest extends FormRequest
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
        $id = $this->route('admin_achievement')?->id;

        return [
            'key' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('achievements', 'key')->ignore($id)],
            'icon' => ['sometimes', 'required', 'string', 'max:16'],
            'name_ar' => ['sometimes', 'required', 'string', 'max:255'],
            'name_en' => ['sometimes', 'required', 'string', 'max:255'],
            'criterion_ar' => ['sometimes', 'required', 'string', 'max:255'],
            'criterion_en' => ['sometimes', 'required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
