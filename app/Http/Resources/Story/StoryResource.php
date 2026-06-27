<?php

namespace App\Http\Resources\Story;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Story\Story
 */
class StoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'story_category_id' => $this->story_category_id,
            'circle_id' => $this->circle_id,
            'title' => $this->title,
            'body' => $this->body,
            'is_anonymous' => $this->is_anonymous,
            'hearts_count' => $this->hearts_count,
            'comments_count' => $this->comments_count,
            'hearted_by_me' => isset($this->hearted_by_me) ? $this->hearted_by_me > 0 : false,
            'user' => (! $this->is_anonymous && $this->relationLoaded('user') && $this->user !== null)
                ? UserResource::make($this->user)->resolve($request)
                : null,
            'category' => ($this->relationLoaded('category') && $this->category !== null)
                ? StoryCategoryResource::make($this->category)->resolve($request)
                : null,
            'circle' => ($this->relationLoaded('circle') && $this->circle !== null)
                ? [
                    'id' => $this->circle->id,
                    'name' => $this->circle->name,
                    'emoji' => $this->circle->emoji,
                    'color' => $this->circle->color,
                    'bg_color' => $this->circle->bg_color,
                ]
                : null,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
