<?php

namespace App\Http\Resources\Wheel;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Wheel\WheelRoomMessage
 */
class WheelRoomMessageResource extends JsonResource
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
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)->resolve($request)),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
