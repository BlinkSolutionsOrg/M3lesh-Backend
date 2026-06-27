<?php

namespace App\Http\Resources\Companion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Companion\CompanionReply
 */
class CompanionReplyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'is_greeting' => $this->is_greeting,
        ];
    }
}
