<?php

namespace App\Http\Resources\Community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A live-feed support-leaf row: who supported whom, in which circle, when.
 *
 * @mixin \App\Models\Community\SupportLeaf
 */
class SupportLeafResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->relationLoaded('user') ? $this->user : null;
        $circle = $this->relationLoaded('circle') ? $this->circle : null;

        return [
            'id' => $this->id,
            'action_type' => $this->action_type,
            'user_name' => $user?->name,
            'circle_id' => $this->circle_id,
            'circle_name' => $circle?->name,
            'circle_emoji' => $circle?->emoji,
            'circle_color' => $circle?->color,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
