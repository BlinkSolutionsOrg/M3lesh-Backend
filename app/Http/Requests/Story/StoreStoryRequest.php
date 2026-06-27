<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoryRequest extends FormRequest
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
            'category_id' => ['nullable', 'integer', 'exists:story_categories,id'],
            'category_key' => ['nullable', 'string', 'exists:story_categories,key'],
            'circle_id' => ['nullable', 'integer', 'exists:circles,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            'is_anonymous' => ['sometimes', 'boolean'],
        ];
    }
}
