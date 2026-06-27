<?php

namespace App\Models\Companion;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanionSuggestion extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'text_ar',
        'text_en',
        'sort_order',
        'is_active',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    /**
     * Locale-based text (ar → text_ar, en → text_en). Fallback to English when empty.
     */
    public function getTextAttribute(): string
    {
        $locale = app()->getLocale();
        $key = 'text_'.($locale === 'ar' ? 'ar' : 'en');
        $value = $this->attributes[$key] ?? null;
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) ($this->attributes['text_en'] ?? $this->attributes['text_ar'] ?? '');
    }
}
