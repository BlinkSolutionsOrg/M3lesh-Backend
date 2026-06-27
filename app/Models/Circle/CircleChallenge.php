<?php

namespace App\Models\Circle;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CircleChallenge extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'circle_id',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'is_active',
        'participants_count',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'circle_id' => 'integer',
        'is_active' => 'boolean',
        'participants_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Locale-based title (ar → title_ar, en → title_en). Fallback to English when empty.
     */
    public function getTitleAttribute(): string
    {
        return $this->localizedColumn('title');
    }

    /**
     * Locale-based description (ar → description_ar, en → description_en). Fallback to English when empty.
     */
    public function getDescriptionAttribute(): string
    {
        return $this->localizedColumn('description');
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

    public function steps(): HasMany
    {
        return $this->hasMany(CircleChallengeStep::class)->orderBy('sort_order');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CircleChallengeMessage::class)->orderByDesc('created_at');
    }
}
