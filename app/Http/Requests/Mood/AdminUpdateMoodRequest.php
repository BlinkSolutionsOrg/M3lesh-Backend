<?php

namespace App\Http\Requests\Mood;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateMoodRequest extends FormRequest
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
        $moodId = $this->route('admin_mood')?->id;

        return [
            'key' => ['sometimes', 'required', 'string', 'max:64', Rule::unique('moods', 'key')->ignore($moodId)],
            'label_ar' => ['sometimes', 'required', 'string', 'max:255'],
            'label_en' => ['sometimes', 'required', 'string', 'max:255'],
            'color' => ['sometimes', 'nullable', 'string', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
            'bg_color' => ['sometimes', 'nullable', 'string', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
            'face_mood' => ['sometimes', 'nullable', 'string', 'in:great,calm,sleepy,anxious,sad,happy'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
