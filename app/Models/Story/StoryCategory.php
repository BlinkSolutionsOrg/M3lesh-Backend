<?php

namespace App\Models\Story;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryCategory extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'key',
        'label_ar',
        'label_en',
        'color',
        'bg_color',
        'border_color',
        'reaction_emoji',
        'reaction_label_ar',
        'reaction_label_en',
        'is_active',
        'sort_order',
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
     * Scope: only active categories (for user-facing API).
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
     * Locale-based label (ar → label_ar, en → label_en). Fallback to English when empty.
     */
    public function getLabelAttribute(): string
    {
        return $this->localizedColumn('label');
    }

    /**
     * Locale-based reaction label (ar → reaction_label_ar, en → reaction_label_en). Fallback to English when empty.
     */
    public function getReactionLabelAttribute(): string
    {
        return $this->localizedColumn('reaction_label');
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

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class)->orderByDesc('created_at');
    }
}
