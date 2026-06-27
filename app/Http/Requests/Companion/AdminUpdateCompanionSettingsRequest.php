<?php

namespace App\Http\Requests\Companion;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateCompanionSettingsRequest extends FormRequest
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
            'help_banner_title_ar' => ['nullable', 'string', 'max:255'],
            'help_banner_title_en' => ['nullable', 'string', 'max:255'],
            'help_banner_body_ar' => ['nullable', 'string', 'max:2000'],
            'help_banner_body_en' => ['nullable', 'string', 'max:2000'],
            'hotline_number' => ['nullable', 'string', 'max:64'],
            'presence_label_ar' => ['nullable', 'string', 'max:255'],
            'presence_label_en' => ['nullable', 'string', 'max:255'],
        ];
    }
}
