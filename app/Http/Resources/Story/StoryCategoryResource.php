<?php

namespace App\Http\Resources\Story;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Story\StoryCategory
 */
class StoryCategoryResource extends JsonResource
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
            'border_color' => $this->border_color,
            'reaction_emoji' => $this->reaction_emoji,
            'reaction_label_ar' => $this->reaction_label_ar,
            'reaction_label_en' => $this->reaction_label_en,
            'reaction_label' => $this->reaction_label,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
