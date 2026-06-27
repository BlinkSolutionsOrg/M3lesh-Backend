<?php

namespace App\Models\Community;

use App\Models\Circle\Circle;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportLeaf extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_season_id',
        'circle_id',
        'action_type',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'community_season_id' => 'integer',
        'circle_id' => 'integer',
        'source_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(CommunitySeason::class, 'community_season_id');
    }
}
