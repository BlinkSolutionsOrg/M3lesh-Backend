<?php

namespace App\Models\Space;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'key',
        'icon',
        'name_ar',
        'name_en',
        'criterion_ar',
        'criterion_en',
        'sort_order',
        'is_active',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Scope: only active achievements (for user-facing API).
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
     * Locale-based name (ar → name_ar, en → name_en). Fallback to English when empty.
     */
    public function getNameAttribute(): string
    {
        return $this->localizedColumn('name');
    }

    /**
     * Locale-based criterion (ar → criterion_ar, en → criterion_en). Fallback to English when empty.
     */
    public function getCriterionAttribute(): string
    {
        return $this->localizedColumn('criterion');
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

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }
}
