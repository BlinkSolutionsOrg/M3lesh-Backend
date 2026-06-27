<?php

namespace App\Models\Community;

use App\Models\Concerns\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityMilestone extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'community_season_id',
        'threshold',
        'label_ar',
        'label_en',
        'reward_type',
        'sort_order',
        'is_active',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'community_season_id' => 'integer',
        'threshold' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Locale-based label (ar → label_ar, en → label_en). Fallback to English when empty.
     */
    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        $key = 'label_'.($locale === 'ar' ? 'ar' : 'en');
        $value = $this->attributes[$key] ?? null;
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) ($this->attributes['label_en'] ?? $this->attributes['label_ar'] ?? '');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(CommunitySeason::class, 'community_season_id');
    }
}
