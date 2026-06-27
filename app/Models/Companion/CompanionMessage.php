<?php

namespace App\Models\Companion;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanionMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const ROLE_USER = 'user';

    public const ROLE_CAT = 'cat';

    protected $fillable = [
        'companion_conversation_id',
        'user_id',
        'role',
        'body',
    ];

    protected $casts = [
        'companion_conversation_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function scopeForConversation(Builder $query, int $conversationId): Builder
    {
        return $query->where('companion_conversation_id', $conversationId);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(CompanionConversation::class, 'companion_conversation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
