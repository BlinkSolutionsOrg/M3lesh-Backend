<?php

namespace App\Http\Resources\Circle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User-facing circle resource.
 *
 * Locale: set Accept-Language (ar|en); name/description follow it. For GET /api/user/*,
 * StripUserApiLocaleNameFields removes the raw *_ar/*_en columns so only localized name/description remain.
 *
 * @mixin \App\Models\Circle\Circle
 */
class CircleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'description' => $this->description,
            'emoji' => $this->emoji,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'members_count' => $this->members_count,
            'joined_by_me' => isset($this->joined_by_me) ? $this->joined_by_me > 0 : false,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
