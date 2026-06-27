<?php

namespace App\Models\Space;

use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FutureLetter extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'recipient_label',
        'body',
        'unlock_at',
        'opened_at',
        'bg_color',
        'text_color',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'user_id' => 'integer',
        'unlock_at' => 'datetime',
        'opened_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function isLocked(): bool
    {
        return $this->unlock_at !== null && $this->unlock_at->isFuture();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
