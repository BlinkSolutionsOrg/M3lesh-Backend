<?php

namespace App\Http\Controllers\Api\User\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Circle\CircleStoryResource;
use App\Models\Circle\CircleStory;
use App\Models\Community\CommunitySeason;
use App\Models\Community\SupportLeaf;
use App\Models\Mood\MoodCheckin;
use App\Models\Mood\MoodStreak;
use App\Models\User\User;
use App\Models\Wheel\WheelChallenge;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Home dashboard aggregate (optional auth): the streak/check-in state, the
 * community pulse, today's wheel challenge and a few recent stories — in one
 * call so the app's home screen is fully backend-driven.
 */
class HomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $userId = $user instanceof User ? $user->id : null;

        return response()->json([
            'data' => [
                'streak' => $this->streak($userId),
                'checked_in_today' => $this->checkedInToday($userId),
                'community' => $this->community(),
                'wheel' => $this->wheel(),
                'stories' => $this->stories($request),
            ],
        ]);
    }

    private function streak(?int $userId): int
    {
        if ($userId === null) {
            return 0;
        }

        return (int) (MoodStreak::query()
            ->where('user_id', $userId)
            ->value('current_streak') ?? 0);
    }

    private function checkedInToday(?int $userId): bool
    {
        if ($userId === null) {
            return false;
        }

        return MoodCheckin::query()
            ->forUser($userId)
            ->whereDate('checkin_date', CarbonImmutable::today()->toDateString())
            ->exists();
    }

    /**
     * @return array{online_now: int, supporters_count: int}
     */
    private function community(): array
    {
        $season = CommunitySeason::active();
        if ($season === null) {
            return ['online_now' => 0, 'supporters_count' => 0];
        }

        // Mirror the community overview: recent leaves + a soft base.
        $lastHour = SupportLeaf::query()
            ->where('community_season_id', $season->id)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return [
            'online_now' => $lastHour + 2000,
            'supporters_count' => (int) $season->leaves_count,
        ];
    }

    /**
     * @return array{title: ?string, responses_count: int}|null
     */
    private function wheel(): ?array
    {
        $challenge = WheelChallenge::query()->active()->latest('id')->first();
        if ($challenge === null) {
            return null;
        }

        return [
            'title' => $challenge->title,
            'responses_count' => (int) ($challenge->responses_count ?? 0),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function stories(Request $request): array
    {
        $stories = CircleStory::query()
            ->with('user')
            ->latest('id')
            ->limit(3)
            ->get();

        return CircleStoryResource::collection($stories)->resolve($request);
    }
}
