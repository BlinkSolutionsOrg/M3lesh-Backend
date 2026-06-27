<?php

namespace App\Http\Resources\Companion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Companion\CompanionSetting
 */
class CompanionSettingsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'help_banner_title_ar' => $this->help_banner_title_ar,
            'help_banner_title_en' => $this->help_banner_title_en,
            'help_banner_title' => $this->help_banner_title,
            'help_banner_body_ar' => $this->help_banner_body_ar,
            'help_banner_body_en' => $this->help_banner_body_en,
            'help_banner_body' => $this->help_banner_body,
            'hotline_number' => $this->hotline_number,
            'presence_label_ar' => $this->presence_label_ar,
            'presence_label_en' => $this->presence_label_en,
            'presence_label' => $this->presence_label,
        ];
    }
}
