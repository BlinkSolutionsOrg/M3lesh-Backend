<?php

namespace App\Http\Resources\Wheel;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Wheel\WheelResponse
 */
class WheelResponseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'wheel_challenge_id' => $this->wheel_challenge_id,
            'body' => $this->body,
            'is_anonymous' => $this->is_anonymous,
            'laugh_count' => $this->laugh_count,
            'heart_count' => $this->heart_count,
            'laugh_reacted_by_me' => isset($this->laugh_reacted_by_me) ? $this->laugh_reacted_by_me > 0 : false,
            'heart_reacted_by_me' => isset($this->heart_reacted_by_me) ? $this->heart_reacted_by_me > 0 : false,
            'user' => (! $this->is_anonymous && $this->relationLoaded('user') && $this->user !== null)
                ? UserResource::make($this->user)->resolve($request)
                : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
