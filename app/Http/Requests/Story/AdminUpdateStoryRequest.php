<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateStoryRequest extends FormRequest
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
            'story_category_id' => ['sometimes', 'nullable', 'integer', 'exists:story_categories,id'],
            'circle_id' => ['sometimes', 'nullable', 'integer', 'exists:circles,id'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string', 'max:2000'],
            'is_anonymous' => ['sometimes', 'boolean'],
        ];
    }
}
