<?php

namespace App\Http\Resources\Story;

use App\Http\Resources\Concerns\ReturnsAuditAdminObject;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Story\Story
 */
class AdminStoryResource extends JsonResource
{
    use ReturnsAuditAdminObject;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'story_category_id' => $this->story_category_id,
            'circle_id' => $this->circle_id,
            'title' => $this->title,
            'body' => $this->body,
            'is_anonymous' => $this->is_anonymous,
            'hearts_count' => $this->hearts_count,
            'comments_count' => $this->comments_count,
            'user' => ($this->relationLoaded('user') && $this->user !== null)
                ? UserResource::make($this->user)->resolve($request)
                : null,
            'category' => ($this->relationLoaded('category') && $this->category !== null)
                ? StoryCategoryResource::make($this->category)->resolve($request)
                : null,
            'circle' => ($this->relationLoaded('circle') && $this->circle !== null)
                ? [
                    'id' => $this->circle->id,
                    'name' => $this->circle->name,
                ]
                : null,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'created_by' => $this->auditAdminObject('creator'),
            'updated_by' => $this->auditAdminObject('updater'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
