<?php

namespace App\Models\Mood;

use App\Models\Concerns\HasAuditFields;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoodCheckin extends Model
{
    use HasAuditFields;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'mood_id',
        'mood_key',
        'color',
        'note',
        'checkin_date',
    ];

    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'user_id' => 'integer',
        'mood_id' => 'integer',
        'checkin_date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mood(): BelongsTo
    {
        return $this->belongsTo(Mood::class);
    }
}
