<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Http\Resources\Space\AchievementResource;
use App\Models\Space\Achievement;
use App\Models\Space\UserAchievement;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    /**
     * Active achievements catalog (read-only, optional auth).
     */
    public function index(Request $request): JsonResponse
    {
        $items = Achievement::query()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'data' => AchievementResource::collection($items)->resolve($request),
        ]);
    }

    /**
     * The catalog merged with the caller's unlocked flags.
     */
    public function mine(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $unlockedIds = UserAchievement::query()
            ->where('user_id', $user->id)
            ->whereNotNull('unlocked_at')
            ->pluck('achievement_id')
            ->all();

        $items = Achievement::query()
            ->active()
            ->ordered()
            ->get()
            ->each(function (Achievement $a) use ($unlockedIds): void {
                $a->unlocked = in_array($a->id, $unlockedIds, true);
            });

        return response()->json([
            'data' => AchievementResource::collection($items)->resolve($request),
        ]);
    }
}
