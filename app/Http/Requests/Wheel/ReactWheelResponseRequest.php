<?php

namespace App\Http\Requests\Wheel;

use App\Models\Wheel\WheelResponseReaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReactWheelResponseRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in([
                WheelResponseReaction::TYPE_LAUGH,
                WheelResponseReaction::TYPE_HEART,
            ])],
        ];
    }
}
