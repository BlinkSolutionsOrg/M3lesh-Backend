<?php

namespace App\Models\Wheel;

use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WheelResponse extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'wheel_challenge_id',
        'user_id',
        'body',
        'is_anonymous',
        'laugh_count',
        'heart_count',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'wheel_challenge_id' => 'integer',
        'user_id' => 'integer',
        'is_anonymous' => 'boolean',
        'laugh_count' => 'integer',
        'heart_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeForChallenge(Builder $query, int $challengeId): Builder
    {
        return $query->where('wheel_challenge_id', $challengeId);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(WheelChallenge::class, 'wheel_challenge_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(WheelResponseReaction::class)->orderByDesc('created_at');
    }
}
