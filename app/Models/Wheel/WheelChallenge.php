<?php

namespace App\Models\Wheel;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WheelChallenge extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'segment_emoji',
        'pill_label_ar',
        'pill_label_en',
        'title_ar',
        'title_en',
        'compose_hint_ar',
        'compose_hint_en',
        'room_banner_ar',
        'room_banner_en',
        'color',
        'bg_color',
        'is_active',
        'spins_count',
        'responses_count',
        'room_messages_count',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'is_active' => 'boolean',
        'spins_count' => 'integer',
        'responses_count' => 'integer',
        'room_messages_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Scope: only active challenges (for user-facing API).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Locale-based pill label (ar → pill_label_ar, en → pill_label_en). Fallback to English when empty.
     */
    public function getPillLabelAttribute(): string
    {
        return $this->localizedColumn('pill_label');
    }

    /**
     * Locale-based title (ar → title_ar, en → title_en). Fallback to English when empty.
     */
    public function getTitleAttribute(): string
    {
        return $this->localizedColumn('title');
    }

    /**
     * Locale-based compose hint (ar → compose_hint_ar, en → compose_hint_en). Fallback to English when empty.
     */
    public function getComposeHintAttribute(): string
    {
        return $this->localizedColumn('compose_hint');
    }

    /**
     * Locale-based room banner (ar → room_banner_ar, en → room_banner_en). Fallback to English when empty.
     */
    public function getRoomBannerAttribute(): string
    {
        return $this->localizedColumn('room_banner');
    }

    private function localizedColumn(string $prefix): string
    {
        $locale = app()->getLocale();
        $key = $prefix.'_'.($locale === 'ar' ? 'ar' : 'en');
        $value = $this->attributes[$key] ?? null;
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) ($this->attributes[$prefix.'_en'] ?? $this->attributes[$prefix.'_ar'] ?? '');
    }

    public function spins(): HasMany
    {
        return $this->hasMany(WheelSpin::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(WheelResponse::class)->orderByDesc('created_at');
    }

    public function roomMessages(): HasMany
    {
        return $this->hasMany(WheelRoomMessage::class)->orderByDesc('created_at');
    }
}
