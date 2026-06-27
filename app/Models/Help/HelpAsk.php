<?php

namespace App\Models\Help;

use App\Models\Circle\Circle;
use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpAsk extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'circle_id',
        'title',
        'body',
        'is_anonymous',
        'status',
        'replies_count',
        'last_activity_at',
    ];

    /** Audit fields are set by the application only; not mass assignable from request. */
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'user_id' => 'integer',
        'circle_id' => 'integer',
        'is_anonymous' => 'boolean',
        'replies_count' => 'integer',
        'last_activity_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Scope: stable ordering for listings (newest first).
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(HelpReply::class)->orderByDesc('votes_count')->orderByDesc('created_at');
    }
}
