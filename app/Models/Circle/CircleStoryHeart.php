<?php

namespace App\Models\Circle;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleStoryHeart extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_story_id',
        'user_id',
    ];

    protected $casts = [
        'circle_story_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(CircleStory::class, 'circle_story_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
