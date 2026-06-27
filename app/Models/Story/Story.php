<?php

namespace App\Models\Story;

use App\Models\Circle\Circle;
use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'story_category_id',
        'circle_id',
        'title',
        'body',
        'is_anonymous',
        'hearts_count',
        'comments_count',
        'last_activity_at',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'user_id' => 'integer',
        'story_category_id' => 'integer',
        'circle_id' => 'integer',
        'is_anonymous' => 'boolean',
        'hearts_count' => 'integer',
        'comments_count' => 'integer',
        'last_activity_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeForCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('story_category_id', $categoryId);
    }

    public function scopeForCircle(Builder $query, int $circleId): Builder
    {
        return $query->where('circle_id', $circleId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StoryCategory::class, 'story_category_id');
    }

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function hearts(): HasMany
    {
        return $this->hasMany(StoryHeart::class)->orderByDesc('created_at');
    }
}
