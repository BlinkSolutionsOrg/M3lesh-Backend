<?php

namespace App\Models\Circle;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleChallengeMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_challenge_id',
        'user_id',
        'body',
    ];

    protected $casts = [
        'circle_challenge_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function scopeForChallenge(Builder $query, int $challengeId): Builder
    {
        return $query->where('circle_challenge_id', $challengeId);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(CircleChallenge::class, 'circle_challenge_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
