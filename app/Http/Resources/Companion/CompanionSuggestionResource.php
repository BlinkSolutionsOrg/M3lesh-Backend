<?php

namespace App\Http\Resources\Companion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Companion\CompanionSuggestion
 */
class CompanionSuggestionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text_ar' => $this->text_ar,
            'text_en' => $this->text_en,
            'text' => $this->text,
            'sort_order' => $this->sort_order,
        ];
    }
}
