<?php

namespace App\Http\Resources\Space;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User-facing achievement (medal). When merged with the caller's unlocked set,
 * the model carries a transient `unlocked` attribute used here.
 *
 * @mixin \App\Models\Space\Achievement
 */
class AchievementResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'icon' => $this->icon,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'criterion_ar' => $this->criterion_ar,
            'criterion_en' => $this->criterion_en,
            'criterion' => $this->criterion,
            'sort_order' => $this->sort_order,
            'unlocked' => (bool) ($this->unlocked ?? false),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
