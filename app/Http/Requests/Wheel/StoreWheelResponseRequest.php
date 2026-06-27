<?php

namespace App\Http\Requests\Wheel;

use Illuminate\Foundation\Http\FormRequest;

class StoreWheelResponseRequest extends FormRequest
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
            'body' => ['required', 'string', 'max:1000'],
            'is_anonymous' => ['sometimes', 'boolean'],
        ];
    }
}
