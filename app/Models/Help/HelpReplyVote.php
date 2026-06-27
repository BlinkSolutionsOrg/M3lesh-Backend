<?php

namespace App\Models\Help;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpReplyVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_reply_id',
        'user_id',
    ];

    protected $casts = [
        'help_reply_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function reply(): BelongsTo
    {
        return $this->belongsTo(HelpReply::class, 'help_reply_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
