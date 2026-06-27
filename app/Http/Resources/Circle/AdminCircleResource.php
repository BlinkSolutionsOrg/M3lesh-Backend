<?php

namespace App\Http\Resources\Circle;

use App\Http\Resources\Concerns\ReturnsAuditAdminObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Circle\Circle
 */
class AdminCircleResource extends JsonResource
{
    use ReturnsAuditAdminObject;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'description' => $this->description,
            'emoji' => $this->emoji,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'members_count' => $this->members_count,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'created_by' => $this->auditAdminObject('creator'),
            'updated_by' => $this->auditAdminObject('updater'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
