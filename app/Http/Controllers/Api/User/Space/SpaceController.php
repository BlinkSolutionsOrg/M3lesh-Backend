<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Models\Space\Achievement;
use App\Models\Space\RoomDecoration;
use App\Models\Space\UserAchievement;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SpaceController extends Controller
{
    /**
     * Resolve the caller's mood streak (created by the mood feature).
     * Falls back to 0 when no streak row exists yet.
     */
    private function streakFor(?User $user): int
    {
        if (! $user instanceof User) {
            return 0;
        }

        if (! Schema::hasTable('mood_streaks')) {
            return 0;
        }

        $current = \App\Models\Mood\MoodStreak::query()
            ->where('user_id', $user->id)
            ->value('current_streak');

        return (int) ($current ?? 0);
    }

    /**
     * Level derived from the mood streak: floor(streak / 7) + 1.
     */
    private function levelFromStreak(int $streak): int
    {
        return intdiv(max($streak, 0), 7) + 1;
    }

    /**
     * Aggregate overview for «مساحتي».
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $streak = $this->streakFor($user instanceof User ? $user : null);
        $level = $this->levelFromStreak($streak);

        $journalCount = 0;
        $gratitudeCount = 0;
        $medalsUnlocked = 0;

        if ($user instanceof User) {
            if (Schema::hasTable('journal_entries')) {
                $journalCount = (int) \DB::table('journal_entries')
                    ->where('user_id', $user->id)
                    ->whereNull('deleted_at')
                    ->count();
            }
            if (Schema::hasTable('gratitude_notes')) {
                $gratitudeCount = (int) \DB::table('gratitude_notes')
                    ->where('user_id', $user->id)
                    ->whereNull('deleted_at')
                    ->count();
            }
            $medalsUnlocked = (int) UserAchievement::query()
                ->where('user_id', $user->id)
                ->whereNotNull('unlocked_at')
                ->count();
        }

        $medalsTotal = (int) Achievement::query()->active()->count();

        return response()->json([
            'data' => [
                'level' => $level,
                'streak' => $streak,
                'journal_count' => $journalCount,
                'gratitude_count' => $gratitudeCount,
                'medals_unlocked' => $medalsUnlocked,
                'medals_total' => $medalsTotal,
            ],
        ]);
    }

    /**
     * Room state for «بيت معلش»: level, progress within the current 7-day cycle,
     * and decorations with an unlocked flag.
     */
    public function room(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $streak = $this->streakFor($user instanceof User ? $user : null);
        $level = $this->levelFromStreak($streak);
        $progress = ($streak % 7) / 7;

        $decorations = RoomDecoration::query()
            ->active()
            ->ordered()
            ->get()
            ->map(fn (RoomDecoration $d) => [
                'key' => $d->key,
                'title' => $d->title,
                'required_level' => $d->required_level,
                'unlocked' => $d->required_level <= $level,
            ])
            ->values();

        return response()->json([
            'data' => [
                'level' => $level,
                'progress' => $progress,
                'decorations' => $decorations,
            ],
        ]);
    }
}
