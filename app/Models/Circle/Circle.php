<?php

namespace App\Models\Circle;

use App\Models\Concerns\HasAuditFields;
use App\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Circle extends Model
{
    use HasAuditFields;
    use HasFactory;
    use HasLocalizedName;
    use SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'emoji',
        'color',
        'bg_color',
        'is_active',
        'sort_order',
        'members_count',
        'last_activity_at',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'members_count' => 'integer',
        'last_activity_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Scope: only active circles (for user-facing API).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: stable ordering for listings (sort_order then newest).
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    /**
     * Locale-based description (ar → description_ar, en → description_en). Fallback to English when empty.
     */
    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        $key = 'description_'.($locale === 'ar' ? 'ar' : 'en');
        $value = $this->attributes[$key] ?? null;
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) ($this->attributes['description_en'] ?? $this->attributes['description_ar'] ?? '');
    }

    public function members(): HasMany
    {
        return $this->hasMany(CircleMember::class);
    }

    public function wins(): HasMany
    {
        return $this->hasMany(CircleWin::class)->orderByDesc('created_at');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(CircleStory::class)->orderByDesc('created_at');
    }

    public function icebreakers(): HasMany
    {
        return $this->hasMany(CircleIcebreaker::class)->orderBy('sort_order');
    }

    public function challenges(): HasMany
    {
        return $this->hasMany(CircleChallenge::class)->orderByDesc('created_at');
    }
}
