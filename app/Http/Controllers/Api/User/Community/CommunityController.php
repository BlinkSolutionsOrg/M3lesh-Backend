<?php

namespace App\Http\Controllers\Api\User\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\StoreSupportLeafRequest;
use App\Http\Resources\Community\CommunityLeaderboardRowResource;
use App\Http\Resources\Community\SupportLeafResource;
use App\Models\Community\CommunityMilestone;
use App\Models\Community\CommunitySeason;
use App\Models\Community\SupportLeaf;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    /**
     * Aggregate dashboard for the active season ("شجرة الدعم").
     * Built inline: small payload, no giant resource.
     */
    public function overview(Request $request): JsonResponse
    {
        $season = CommunitySeason::active();
        if ($season === null) {
            return response()->json(['data' => null]);
        }

        $user = $request->user('sanctum');
        $userId = $user instanceof User ? $user->id : null;

        $liveCount = (int) $season->leaves_count;

        // online_now: leaves in the last hour + a small base (approximation).
        $lastHour = SupportLeaf::query()
            ->where('community_season_id', $season->id)
            ->where('created_at', '>=', now()->subHour())
            ->count();
        $onlineNow = $lastHour + 2000;

        // recent_delta: leaves in the last few minutes.
        $recentDelta = SupportLeaf::query()
            ->where('community_season_id', $season->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        $myLeavesCount = 0;
        $myStreakDays = 0;
        if ($userId !== null) {
            $myLeavesCount = SupportLeaf::query()
                ->where('community_season_id', $season->id)
                ->where('user_id', $userId)
                ->count();
            $myStreakDays = $this->streakDays($season->id, $userId);
        }

        // circles_leaderboard: leaves grouped by circle, joined to circles.
        $grouped = SupportLeaf::query()
            ->select('support_leaves.circle_id', DB::raw('COUNT(*) as leaves_count'))
            ->join('circles', 'circles.id', '=', 'support_leaves.circle_id')
            ->where('support_leaves.community_season_id', $season->id)
            ->whereNotNull('support_leaves.circle_id')
            ->groupBy('support_leaves.circle_id')
            ->orderByDesc('leaves_count')
            ->limit(5)
            ->with('circle')
            ->get();

        $topCount = (int) ($grouped->max('leaves_count') ?? 0);
        $locale = app()->getLocale();
        $leaderboard = $grouped->map(function (SupportLeaf $row) use ($topCount, $locale) {
            $circle = $row->circle;
            $count = (int) $row->leaves_count;
            $name = $circle === null
                ? null
                : ($locale === 'ar'
                    ? ($circle->name_ar ?: $circle->name_en)
                    : ($circle->name_en ?: $circle->name_ar));

            return (object) [
                'circle_id' => $row->circle_id,
                'name' => $name,
                'emoji' => $circle?->emoji,
                'color' => $circle?->color,
                'leaves_count' => $count,
                'pct' => $topCount > 0 ? round($count / $topCount, 4) : 0,
            ];
        });

        // live_feed: latest ~10 leaves.
        $feed = SupportLeaf::query()
            ->where('community_season_id', $season->id)
            ->with(['user', 'circle'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // milestones: active season milestones with done + tag.
        $milestones = CommunityMilestone::query()
            ->where('community_season_id', $season->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (CommunityMilestone $m) use ($liveCount) {
                $done = $m->threshold <= $liveCount;

                return [
                    'id' => $m->id,
                    'threshold' => $m->threshold,
                    'label_ar' => $m->label_ar,
                    'label_en' => $m->label_en,
                    'label' => $m->label,
                    'reward_type' => $m->reward_type,
                    'sort_order' => $m->sort_order,
                    'done' => $done,
                    'tag' => $done ? 'done' : 'locked',
                ];
            });

        // ticker: a few recent action labels.
        $ticker = $feed->take(6)->map(fn (SupportLeaf $l) => $l->action_type)->values();

        return response()->json([
            'data' => [
                'season' => [
                    'name' => $season->name,
                    'goal_leaves' => (int) $season->goal_leaves,
                ],
                'live_count' => $liveCount,
                'online_now' => $onlineNow,
                'recent_delta' => $recentDelta,
                'my_leaves_count' => $myLeavesCount,
                'my_streak_days' => $myStreakDays,
                'circles_leaderboard' => CommunityLeaderboardRowResource::collection($leaderboard)->resolve($request),
                'live_feed' => SupportLeafResource::collection($feed)->resolve($request),
                'milestones' => $milestones,
                'ticker' => $ticker,
            ],
        ]);
    }

    /**
     * Distinct consecutive days (ending today or yesterday) the user earned a leaf.
     */
    private function streakDays(int $seasonId, int $userId): int
    {
        $days = SupportLeaf::query()
            ->where('community_season_id', $seasonId)
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->pluck('created_at')
            ->map(fn ($d) => $d->toDateString())
            ->unique()
            ->values();

        if ($days->isEmpty()) {
            return 0;
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        if ($days->first() !== $today && $days->first() !== $yesterday) {
            return 0;
        }

        $streak = 1;
        for ($i = 1; $i < $days->count(); $i++) {
            // Carbon 3 diffInDays() returns a signed float by default; pass
            // absolute=true and cast to int so the consecutive-day check is reliable.
            $prev = \Carbon\Carbon::parse($days[$i - 1])->startOfDay();
            $cur = \Carbon\Carbon::parse($days[$i])->startOfDay();
            if ((int) $prev->diffInDays($cur, true) === 1) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Plant a support leaf in the active season for the auth user.
     */
    public function storeLeaf(StoreSupportLeafRequest $request): JsonResponse
    {
        $season = CommunitySeason::active();
        if ($season === null) {
            abort(404);
        }

        $validated = $request->validated();

        SupportLeaf::create([
            'user_id' => $request->user()->id,
            'community_season_id' => $season->id,
            'circle_id' => $validated['circle_id'] ?? null,
            'action_type' => $validated['action_type'],
        ]);

        $season->increment('leaves_count');
        $season->refresh();

        return response()->json([
            'leaves_count' => (int) $season->leaves_count,
        ], 201);
    }
}
