<?php

namespace App\Http\Resources\Mood;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User-facing mood resource.
 *
 * Locale: set Accept-Language (ar|en); label follows it. For GET /api/user/*,
 * StripUserApiLocaleNameFields removes the raw label_ar/label_en columns so only the localized label remains.
 *
 * @mixin \App\Models\Mood\Mood
 */
class MoodResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'label_ar' => $this->label_ar,
            'label_en' => $this->label_en,
            'label' => $this->label,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'face_mood' => $this->face_mood,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
