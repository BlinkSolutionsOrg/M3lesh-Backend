<?php

namespace App\Models\Community;

use App\Models\Concerns\HasAuditFields;
use App\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunitySeason extends Model
{
    use HasAuditFields;
    use HasFactory;
    use HasLocalizedName;
    use SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'goal_leaves',
        'leaves_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'goal_leaves' => 'integer',
        'leaves_count' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Scope: only active seasons.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * The active season = latest active community_season.
     */
    public static function active(): ?self
    {
        return static::query()->active()->latest('id')->first();
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(CommunityMilestone::class)->orderBy('sort_order');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(SupportLeaf::class);
    }
}
