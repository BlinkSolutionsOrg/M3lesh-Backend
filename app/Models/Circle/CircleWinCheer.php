<?php

namespace App\Models\Circle;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleWinCheer extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_win_id',
        'user_id',
    ];

    protected $casts = [
        'circle_win_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function win(): BelongsTo
    {
        return $this->belongsTo(CircleWin::class, 'circle_win_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
