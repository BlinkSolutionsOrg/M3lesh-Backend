<?php

namespace Database\Seeders;

use App\Models\Mood\Mood;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedMoods();
    }

    /**
     * The six moods shown on the check-in grid — a direct port of the app's `kMoods`.
     */
    private function seedMoods(): void
    {
        $moods = [
            ['key' => 'great', 'label_ar' => 'مبسوط', 'label_en' => 'Great', 'color' => '#5BB98B', 'bg_color' => '#EAF6EF', 'face_mood' => 'great', 'sort_order' => 1],
            ['key' => 'ok', 'label_ar' => 'ماشي الحال', 'label_en' => 'Okay', 'color' => '#D7972A', 'bg_color' => '#FBF1DE', 'face_mood' => 'calm', 'sort_order' => 2],
            ['key' => 'tired', 'label_ar' => 'تعبان', 'label_en' => 'Tired', 'color' => '#D9743F', 'bg_color' => '#FBEADF', 'face_mood' => 'sleepy', 'sort_order' => 3],
            ['key' => 'anxious', 'label_ar' => 'قلقان', 'label_en' => 'Anxious', 'color' => '#5E82C9', 'bg_color' => '#E8F0FB', 'face_mood' => 'anxious', 'sort_order' => 4],
            ['key' => 'bored', 'label_ar' => 'زهقان', 'label_en' => 'Bored', 'color' => '#9B72C0', 'bg_color' => '#F1E9F8', 'face_mood' => 'calm', 'sort_order' => 5],
            ['key' => 'sad', 'label_ar' => 'مفيش مزاج', 'label_en' => 'No mood', 'color' => '#8E8290', 'bg_color' => '#F1EDF0', 'face_mood' => 'sad', 'sort_order' => 6],
        ];

        foreach ($moods as $data) {
            $mood = Mood::withTrashed()->updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['is_active' => true]),
            );
            if ($mood->trashed()) {
                $mood->restore();
            }
            $this->setAuditIfNew($mood);
        }
    }
}
