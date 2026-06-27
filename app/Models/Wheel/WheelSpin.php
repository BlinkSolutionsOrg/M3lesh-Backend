<?php

namespace App\Models\Wheel;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WheelSpin extends Model
{
    use HasFactory;

    protected $fillable = [
        'wheel_challenge_id',
        'user_id',
    ];

    protected $casts = [
        'wheel_challenge_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(WheelChallenge::class, 'wheel_challenge_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
