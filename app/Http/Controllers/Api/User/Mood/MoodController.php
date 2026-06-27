<?php

namespace App\Http\Controllers\Api\User\Mood;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mood\StoreMoodCheckinRequest;
use App\Http\Resources\Mood\MoodCheckinResource;
use App\Http\Resources\Mood\MoodResource;
use App\Models\Mood\Mood;
use App\Models\Mood\MoodCheckin;
use App\Models\Mood\MoodStreak;
use App\Services\Mood\MoodCheckinService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    public function __construct(
        private MoodCheckinService $checkinService,
    ) {}

    /**
     * The active mood catalog (ordered), as a plain { data: [...] } payload.
     */
    public function index(Request $request): JsonResponse
    {
        $moods = Mood::query()->active()->ordered()->get();

        return response()->json([
            'data' => MoodResource::collection($moods)->resolve($request),
        ]);
    }

    /**
     * Today's check-in for the authenticated user, or { data: null }.
     */
    public function today(Request $request): JsonResponse
    {
        $today = CarbonImmutable::today()->toDateString();

        $checkin = MoodCheckin::query()
            ->forUser($request->user()->id)
            ->whereDate('checkin_date', $today)
            ->first();

        if ($checkin === null) {
            return response()->json(['data' => null]);
        }

        return MoodCheckinResource::make($checkin)->response($request);
    }

    /**
     * Record (or update) today's check-in and recompute the streak.
     */
    public function checkin(StoreMoodCheckinRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->checkinService->checkin(
            $request->user(),
            $validated['mood_key'],
            $validated['note'] ?? null,
        );

        return response()->json([
            'checkin' => MoodCheckinResource::make($result['checkin'])->resolve($request),
            'current_streak' => $result['current_streak'],
            'longest_streak' => $result['longest_streak'],
        ]);
    }

    /**
     * Garden payload: streak counters + the last 7 days as colored bars.
     */
    public function garden(Request $request): JsonResponse
    {
        $user = $request->user();

        $streak = MoodStreak::query()->where('user_id', $user->id)->first();

        $start = CarbonImmutable::today()->subDays(6);
        $checkins = MoodCheckin::query()
            ->forUser($user->id)
            ->whereDate('checkin_date', '>=', $start->toDateString())
            ->get()
            ->keyBy(fn (MoodCheckin $c) => $c->checkin_date->toDateString());

        $labelsAr = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $start->addDays($i);
            $key = $day->toDateString();
            $checkin = $checkins->get($key);
            $week[] = [
                'date' => $key,
                'label_ar' => $labelsAr[(int) $day->dayOfWeek],
                'color' => $checkin?->color ?: '#E3DBE8',
            ];
        }

        return response()->json([
            'current_streak' => (int) ($streak->current_streak ?? 0),
            'longest_streak' => (int) ($streak->longest_streak ?? 0),
            'week' => $week,
        ]);
    }
}
