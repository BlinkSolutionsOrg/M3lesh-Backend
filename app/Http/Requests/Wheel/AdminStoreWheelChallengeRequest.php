<?php

namespace App\Http\Requests\Wheel;

use App\Filament\Resources\WheelChallenge\Schemas\WheelChallengeForm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminStoreWheelChallengeRequest extends FormRequest
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
            'segment_emoji' => ['required', 'string', Rule::in(WheelChallengeForm::SEGMENT_EMOJIS)],
            'pill_label_ar' => ['required', 'string', 'max:255'],
            'pill_label_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'compose_hint_ar' => ['nullable', 'string', 'max:5000'],
            'compose_hint_en' => ['nullable', 'string', 'max:5000'],
            'room_banner_ar' => ['nullable', 'string', 'max:5000'],
            'room_banner_en' => ['nullable', 'string', 'max:5000'],
            'color' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
            'bg_color' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
