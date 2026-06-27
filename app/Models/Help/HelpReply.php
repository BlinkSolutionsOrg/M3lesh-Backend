<?php

namespace App\Models\Help;

use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpReply extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'help_ask_id',
        'user_id',
        'type',
        'body',
        'is_anonymous',
        'votes_count',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'help_ask_id' => 'integer',
        'user_id' => 'integer',
        'is_anonymous' => 'boolean',
        'votes_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeForAsk(Builder $query, int $askId): Builder
    {
        return $query->where('help_ask_id', $askId);
    }

    public function ask(): BelongsTo
    {
        return $this->belongsTo(HelpAsk::class, 'help_ask_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(HelpReplyVote::class)->orderByDesc('created_at');
    }
}
