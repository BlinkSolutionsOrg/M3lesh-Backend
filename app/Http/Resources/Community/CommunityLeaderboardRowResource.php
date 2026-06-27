<?php

namespace App\Http\Resources\Community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One circle row on the most-supportive-circles leaderboard.
 *
 * Wraps a plain object/array assembled in the overview controller with keys:
 * circle_id, name, emoji, color, leaves_count, pct.
 *
 * @mixin object
 */
class CommunityLeaderboardRowResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'circle_id' => $this->circle_id,
            'name' => $this->name,
            'emoji' => $this->emoji,
            'color' => $this->color,
            'leaves_count' => $this->leaves_count,
            'pct' => $this->pct,
        ];
    }
}
