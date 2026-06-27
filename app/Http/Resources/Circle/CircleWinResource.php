<?php

namespace App\Http\Resources\Circle;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Circle\CircleWin
 */
class CircleWinResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'circle_id' => $this->circle_id,
            'body' => $this->body,
            'is_anonymous' => $this->is_anonymous,
            'cheers_count' => $this->cheers_count,
            'cheered_by_me' => isset($this->cheered_by_me) ? $this->cheered_by_me > 0 : false,
            'user' => (! $this->is_anonymous && $this->relationLoaded('user') && $this->user !== null)
                ? UserResource::make($this->user)->resolve($request)
                : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
