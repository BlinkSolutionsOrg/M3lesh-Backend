<?php

namespace App\Models\Wheel;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WheelResponseReaction extends Model
{
    use HasFactory;

    public const TYPE_LAUGH = 'laugh';

    public const TYPE_HEART = 'heart';

    protected $fillable = [
        'wheel_response_id',
        'user_id',
        'type',
    ];

    protected $casts = [
        'wheel_response_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(WheelResponse::class, 'wheel_response_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
