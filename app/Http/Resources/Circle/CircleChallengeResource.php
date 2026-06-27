<?php

namespace App\Http\Resources\Circle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Active circle challenge with its steps (+ the caller's completion flags) and stats.
 *
 * @mixin \App\Models\Circle\CircleChallenge
 */
class CircleChallengeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $stepsLoaded = $this->relationLoaded('steps');
        $stepsCount = $stepsLoaded
            ? $this->steps->count()
            : ($this->steps_count ?? null);
        $myCompleted = $stepsLoaded
            ? $this->steps->filter(fn ($step) => isset($step->done_by_me) && $step->done_by_me > 0)->count()
            : 0;

        return [
            'id' => $this->id,
            'circle_id' => $this->circle_id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'title' => $this->title,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'participants_count' => $this->participants_count,
            'steps_count' => $stepsCount,
            'my_completed_steps_count' => $myCompleted,
            'steps' => $this->whenLoaded(
                'steps',
                fn () => CircleChallengeStepResource::collection($this->steps)->resolve($request),
            ),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
