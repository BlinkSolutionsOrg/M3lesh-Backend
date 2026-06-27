<?php

namespace App\Http\Resources\Space;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Space\FutureLetter
 */
class FutureLetterResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isLocked = $this->isLocked();

        return [
            'id' => $this->id,
            'recipient_label' => $this->recipient_label,
            'body' => $isLocked ? null : $this->body,
            'is_locked' => $isLocked,
            'unlock_at' => $this->unlock_at?->toIso8601String(),
            'opened_at' => $this->opened_at?->toIso8601String(),
            'bg_color' => $this->bg_color,
            'text_color' => $this->text_color,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
