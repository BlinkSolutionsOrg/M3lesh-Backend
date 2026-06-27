<?php

namespace App\Http\Resources\Space;

use App\Http\Resources\Concerns\ReturnsAuditAdminObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Space\Achievement
 */
class AdminAchievementResource extends JsonResource
{
    use ReturnsAuditAdminObject;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'icon' => $this->icon,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'criterion_ar' => $this->criterion_ar,
            'criterion_en' => $this->criterion_en,
            'criterion' => $this->criterion,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'created_by' => $this->auditAdminObject('creator'),
            'updated_by' => $this->auditAdminObject('updater'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
