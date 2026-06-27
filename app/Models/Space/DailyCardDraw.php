<?php

namespace App\Models\Space;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCardDraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'daily_card_tip_id',
        'draw_date',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'daily_card_tip_id' => 'integer',
        'draw_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tip(): BelongsTo
    {
        return $this->belongsTo(DailyCardTip::class, 'daily_card_tip_id');
    }
}
