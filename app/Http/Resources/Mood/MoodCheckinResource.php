<?php

namespace App\Http\Resources\Mood;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Mood\MoodCheckin
 */
class MoodCheckinResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mood_id' => $this->mood_id,
            'mood_key' => $this->mood_key,
            'color' => $this->color,
            'note' => $this->note,
            'checkin_date' => $this->checkin_date?->toDateString(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
