<?php

namespace App\Models\Circle;

use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CircleStory extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'circle_id',
        'user_id',
        'body',
        'is_anonymous',
        'hearts_count',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'circle_id' => 'integer',
        'user_id' => 'integer',
        'is_anonymous' => 'boolean',
        'hearts_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeForCircle(Builder $query, int $circleId): Builder
    {
        return $query->where('circle_id', $circleId);
    }

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hearts(): HasMany
    {
        return $this->hasMany(CircleStoryHeart::class)->orderByDesc('created_at');
    }
}
