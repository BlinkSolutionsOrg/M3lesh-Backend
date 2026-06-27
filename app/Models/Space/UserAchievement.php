<?php

namespace App\Models\Space;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'achievement_id',
        'user_id',
        'unlocked_at',
    ];

    protected $casts = [
        'achievement_id' => 'integer',
        'user_id' => 'integer',
        'unlocked_at' => 'datetime',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
