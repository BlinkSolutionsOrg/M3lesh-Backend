<?php

namespace App\Http\Resources\Wheel;

use App\Http\Resources\Concerns\ReturnsAuditAdminObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Wheel\WheelChallenge
 */
class AdminWheelChallengeResource extends JsonResource
{
    use ReturnsAuditAdminObject;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'segment_emoji' => $this->segment_emoji,
            'pill_label_ar' => $this->pill_label_ar,
            'pill_label_en' => $this->pill_label_en,
            'pill_label' => $this->pill_label,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'title' => $this->title,
            'compose_hint_ar' => $this->compose_hint_ar,
            'compose_hint_en' => $this->compose_hint_en,
            'compose_hint' => $this->compose_hint,
            'room_banner_ar' => $this->room_banner_ar,
            'room_banner_en' => $this->room_banner_en,
            'room_banner' => $this->room_banner,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'is_active' => $this->is_active,
            'spins_count' => $this->spins_count,
            'responses_count' => $this->responses_count,
            'room_messages_count' => $this->room_messages_count,
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'created_by' => $this->auditAdminObject('creator'),
            'updated_by' => $this->auditAdminObject('updater'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
