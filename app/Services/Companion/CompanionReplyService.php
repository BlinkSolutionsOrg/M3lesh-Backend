<?php

namespace App\Services\Companion;

use App\Models\Companion\CompanionReply;
use Illuminate\Support\Collection;

/**
 * Picks the cat's reply from the admin-authored pool. Faithful to the original
 * local canned behaviour: a greeting on the first message, then a weighted
 * random pick from the active non-greeting pool.
 */
class CompanionReplyService
{
    /**
     * The greeting reply seeded for the first cat message, or null.
     */
    public function greeting(): ?CompanionReply
    {
        return CompanionReply::query()
            ->active()
            ->where('is_greeting', true)
            ->inRandomOrder()
            ->first();
    }

    /**
     * A weighted random active non-greeting reply, or null when the pool is empty.
     */
    public function randomReply(): ?CompanionReply
    {
        /** @var Collection<int, CompanionReply> $replies */
        $replies = CompanionReply::query()
            ->active()
            ->where('is_greeting', false)
            ->get();

        if ($replies->isEmpty()) {
            return null;
        }

        $totalWeight = (int) $replies->sum(fn (CompanionReply $r) => max(1, $r->weight));
        $roll = random_int(1, $totalWeight);

        $cursor = 0;
        foreach ($replies as $reply) {
            $cursor += max(1, $reply->weight);
            if ($roll <= $cursor) {
                return $reply;
            }
        }

        return $replies->last();
    }
}
