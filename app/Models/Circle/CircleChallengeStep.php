<?php

namespace App\Models\Circle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CircleChallengeStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_challenge_id',
        'text_ar',
        'text_en',
        'sort_order',
    ];

    protected $casts = [
        'circle_challenge_id' => 'integer',
        'sort_order' => 'integer',
    ];

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

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(CircleChallenge::class, 'circle_challenge_id');
    }

    public function completions(): HasMany
    {
        return $this->hasMany(CircleChallengeStepCompletion::class);
    }
}
