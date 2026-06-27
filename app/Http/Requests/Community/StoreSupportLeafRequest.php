<?php

namespace App\Http\Requests\Community;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportLeafRequest extends FormRequest
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
            'action_type' => ['required', 'string', 'in:support,advice,win,checkin,kind_word'],
            'circle_id' => ['nullable', 'integer', 'exists:circles,id'],
        ];
    }
}
