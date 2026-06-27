<?php

namespace App\Http\Resources\Circle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Circle\CircleIcebreaker
 */
class CircleIcebreakerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'circle_id' => $this->circle_id,
            'tag_ar' => $this->tag_ar,
            'tag_en' => $this->tag_en,
            'tag' => $this->tag,
            'question_ar' => $this->question_ar,
            'question_en' => $this->question_en,
            'question' => $this->question,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
