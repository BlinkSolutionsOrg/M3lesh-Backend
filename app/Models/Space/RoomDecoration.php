<?php

namespace App\Models\Space;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomDecoration extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'key',
        'title_ar',
        'title_en',
        'required_level',
        'is_active',
        'sort_order',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'is_active' => 'boolean',
        'required_level' => 'integer',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Scope: only active decorations (for user-facing API).
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
     * Locale-based title (ar → title_ar, en → title_en). Fallback to English when empty.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $key = 'title_'.($locale === 'ar' ? 'ar' : 'en');
        $value = $this->attributes[$key] ?? null;
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) ($this->attributes['title_en'] ?? $this->attributes['title_ar'] ?? '');
    }
}
