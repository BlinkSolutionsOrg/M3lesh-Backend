<?php

namespace App\Http\Resources\Help;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Help\HelpReply
 */
class HelpReplyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'help_ask_id' => $this->help_ask_id,
            'type' => $this->type,
            'body' => $this->body,
            'is_anonymous' => $this->is_anonymous,
            'votes_count' => $this->votes_count,
            'voted_by_me' => isset($this->voted_by_me) ? $this->voted_by_me > 0 : false,
            'user' => (! $this->is_anonymous && $this->relationLoaded('user') && $this->user !== null)
                ? UserResource::make($this->user)->resolve($request)
                : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
