<?php

namespace App\Http\Resources\Help;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Help\HelpAsk
 */
class HelpAskResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'circle_id' => $this->circle_id,
            'circle_name' => ($this->relationLoaded('circle') && $this->circle !== null)
                ? $this->circle->name
                : null,
            'title' => $this->title,
            'body' => $this->body,
            'is_anonymous' => $this->is_anonymous,
            'status' => $this->status,
            'replies_count' => $this->replies_count,
            'user' => (! $this->is_anonymous && $this->relationLoaded('user') && $this->user !== null)
                ? UserResource::make($this->user)->resolve($request)
                : null,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
