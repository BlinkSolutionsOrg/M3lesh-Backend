<?php

namespace App\Models\Companion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_banner_title_ar',
        'help_banner_title_en',
        'help_banner_body_ar',
        'help_banner_body_en',
        'hotline_number',
        'presence_label_ar',
        'presence_label_en',
    ];

    /**
     * The single settings row (created on first access).
     */
    public static function singleton(): self
    {
        return static::query()->firstOrCreate([]);
    }

    private function localized(string $prefix): string
    {
        $locale = app()->getLocale();
        $key = $prefix.'_'.($locale === 'ar' ? 'ar' : 'en');
        $value = $this->attributes[$key] ?? null;
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) ($this->attributes[$prefix.'_en'] ?? $this->attributes[$prefix.'_ar'] ?? '');
    }

    public function getHelpBannerTitleAttribute(): string
    {
        return $this->localized('help_banner_title');
    }

    public function getHelpBannerBodyAttribute(): string
    {
        return $this->localized('help_banner_body');
    }

    public function getPresenceLabelAttribute(): string
    {
        return $this->localized('presence_label');
    }
}
