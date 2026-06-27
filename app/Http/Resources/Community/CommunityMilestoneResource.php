<?php

namespace App\Http\Resources\Community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A milestone row on the shared-journey timeline.
 *
 * @mixin \App\Models\Community\CommunityMilestone
 */
class CommunityMilestoneResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'threshold' => $this->threshold,
            'label_ar' => $this->label_ar,
            'label_en' => $this->label_en,
            'label' => $this->label,
            'reward_type' => $this->reward_type,
            'sort_order' => $this->sort_order,
            // `done` and `tag` are injected by the overview controller.
            'done' => $this->done ?? false,
            'tag' => $this->tag ?? null,
        ];
    }
}
