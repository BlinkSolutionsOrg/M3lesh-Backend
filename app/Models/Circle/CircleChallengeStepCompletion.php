<?php

namespace App\Models\Circle;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleChallengeStepCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_challenge_step_id',
        'user_id',
    ];

    protected $casts = [
        'circle_challenge_step_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(CircleChallengeStep::class, 'circle_challenge_step_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
