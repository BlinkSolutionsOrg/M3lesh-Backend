<?php

namespace App\Services\Mood;

use App\Models\Mood\Mood;
use App\Models\Mood\MoodCheckin;
use App\Models\Mood\MoodStreak;
use App\Models\User\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class MoodCheckinService
{
    /**
     * Record (or update) today's check-in for the user and recompute the streak.
     *
     * @return array{checkin: MoodCheckin, current_streak: int, longest_streak: int}
     */
    public function checkin(User $user, string $moodKey, ?string $note): array
    {
        return DB::transaction(function () use ($user, $moodKey, $note): array {
            $today = CarbonImmutable::today()->toDateString();

            $mood = Mood::query()->where('key', $moodKey)->first();

            $checkin = MoodCheckin::updateOrCreate(
                ['user_id' => $user->id, 'checkin_date' => $today],
                [
                    'mood_id' => $mood?->id,
                    'mood_key' => $moodKey,
                    'color' => $mood?->color,
                    'note' => $note,
                ],
            );

            $streak = MoodStreak::firstOrNew(['user_id' => $user->id]);

            $last = $streak->last_checkin_date?->toDateString();
            $yesterday = CarbonImmutable::today()->subDay()->toDateString();

            if ($last === $today) {
                // Already checked in today — streak unchanged.
                $current = max(1, (int) $streak->current_streak);
            } elseif ($last === $yesterday) {
                $current = (int) $streak->current_streak + 1;
            } else {
                $current = 1;
            }

            $streak->current_streak = $current;
            $streak->longest_streak = max((int) $streak->longest_streak, $current);
            $streak->last_checkin_date = $today;
            $streak->save();

            return [
                'checkin' => $checkin,
                'current_streak' => (int) $streak->current_streak,
                'longest_streak' => (int) $streak->longest_streak,
            ];
        });
    }
}
