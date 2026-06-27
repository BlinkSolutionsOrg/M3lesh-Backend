<?php

namespace App\Models\Circle;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_id',
        'user_id',
    ];

    protected $casts = [
        'circle_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
