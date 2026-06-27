<?php

namespace App\Http\Resources\Circle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Circle\CircleChallengeStep
 */
class CircleChallengeStepResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text_ar' => $this->text_ar,
            'text_en' => $this->text_en,
            'text' => $this->text,
            'sort_order' => $this->sort_order,
            // "done" checkmark for the current user (withCount alias `done_by_me`).
            'done' => isset($this->done_by_me) ? $this->done_by_me > 0 : false,
        ];
    }
}
