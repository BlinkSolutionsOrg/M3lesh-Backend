<?php

namespace App\Models\Circle;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CircleIcebreaker extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'circle_id',
        'tag_ar',
        'tag_en',
        'question_ar',
        'question_en',
        'color',
        'bg_color',
        'is_active',
        'sort_order',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'circle_id' => 'integer',
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
     * Locale-based tag (ar → tag_ar, en → tag_en). Fallback to English when empty.
     */
    public function getTagAttribute(): string
    {
        return $this->localizedColumn('tag');
    }

    /**
     * Locale-based question (ar → question_ar, en → question_en). Fallback to English when empty.
     */
    public function getQuestionAttribute(): string
    {
        return $this->localizedColumn('question');
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

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }
}
