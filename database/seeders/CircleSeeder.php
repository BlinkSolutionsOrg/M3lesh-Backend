<?php

namespace Database\Seeders;

use App\Models\Circle\Circle;
use App\Models\Circle\CircleChallenge;
use App\Models\Circle\CircleChallengeStep;
use App\Models\Circle\CircleIcebreaker;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class CircleSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedCircles();
        $this->seedIcebreakers();
        $this->seedWorkStressChallenge();
    }

    /**
     * The six support circles — a direct port of the app's `kCircles`.
     */
    private function seedCircles(): void
    {
        $circles = [
            ['name_en' => 'Anxiety', 'name_ar' => 'القلق', 'description_ar' => 'لما الدنيا تبقى تقيلة', 'description_en' => 'When everything feels heavy', 'emoji' => '🌊', 'color' => '#5E82C9', 'bg_color' => '#E8F0FB', 'sort_order' => 1],
            ['name_en' => 'Breakup', 'name_ar' => 'فراق', 'description_ar' => 'بنعدّي الوجع سوا', 'description_en' => 'Getting through the pain together', 'emoji' => '🥀', 'color' => '#D86A86', 'bg_color' => '#FBE9EF', 'sort_order' => 2],
            ['name_en' => 'Work Stress', 'name_ar' => 'ضغط الشغل', 'description_ar' => 'الشغل واخدنا منّنا', 'description_en' => 'Work is taking us from ourselves', 'emoji' => '☕', 'color' => '#D7972A', 'bg_color' => '#FBF1DE', 'sort_order' => 3],
            ['name_en' => 'Post-Graduation', 'name_ar' => 'توهان بعد التخرّج', 'description_ar' => 'وبعدين يعني؟', 'description_en' => 'So… what now?', 'emoji' => '🧭', 'color' => '#3FA98C', 'bg_color' => '#E6F5EF', 'sort_order' => 4],
            ['name_en' => 'Family Problems', 'name_ar' => 'مشاكل الأهل', 'description_ar' => 'البيت اللي بيوجع', 'description_en' => 'The home that hurts', 'emoji' => '🏠', 'color' => '#7A9A4E', 'bg_color' => '#EEF4E4', 'sort_order' => 5],
            ['name_en' => 'Loss', 'name_ar' => 'فقد حد', 'description_ar' => 'الغيبة اللي بتفضل', 'description_en' => 'The absence that lingers', 'emoji' => '🕊️', 'color' => '#8E7BB3', 'bg_color' => '#F1ECF8', 'sort_order' => 6],
        ];

        foreach ($circles as $data) {
            $circle = Circle::withTrashed()->updateOrCreate(
                ['name_en' => $data['name_en']],
                array_merge($data, ['is_active' => true]),
            );
            if ($circle->trashed()) {
                $circle->restore();
            }
            $this->setAuditIfNew($circle);
        }
    }

    /**
     * Global icebreaker cards — a direct port of the app's `kIceQuestions`.
     */
    private function seedIcebreakers(): void
    {
        $cards = [
            ['tag_ar' => 'لو…', 'tag_en' => 'If…', 'question_ar' => 'لو تقدر ترجع الزمن يوم واحد، هتختار إيه؟', 'question_en' => 'If you could go back one day in time, which would you pick?', 'color' => '#5E82C9', 'bg_color' => '#E8F0FB', 'sort_order' => 1],
            ['tag_ar' => 'أكتر حاجة…', 'tag_en' => 'The thing that…', 'question_ar' => 'أكتر حاجة بتفرحك في يومك العادي؟', 'question_en' => 'What makes you happiest in an ordinary day?', 'color' => '#3D6B52', 'bg_color' => '#EAF6EF', 'sort_order' => 2],
            ['tag_ar' => 'احكيلنا…', 'tag_en' => 'Tell us…', 'question_ar' => 'موقف صغير ضحّكك الأسبوع ده؟', 'question_en' => 'A small moment that made you laugh this week?', 'color' => '#D7972A', 'bg_color' => '#FBF1DE', 'sort_order' => 3],
            ['tag_ar' => 'لو…', 'tag_en' => 'If…', 'question_ar' => 'لو الضغط بقى شكل، هيبقى شكله إيه عندك؟', 'question_en' => 'If stress had a shape, what would yours look like?', 'color' => '#6E5C86', 'bg_color' => '#F1ECF8', 'sort_order' => 4],
            ['tag_ar' => 'أكتر حاجة…', 'tag_en' => 'The thing that…', 'question_ar' => 'أكتر حاجة محتاج تسمعها دلوقتي؟', 'question_en' => 'What do you most need to hear right now?', 'color' => '#C2685A', 'bg_color' => '#FBEDE9', 'sort_order' => 5],
        ];

        foreach ($cards as $data) {
            $card = CircleIcebreaker::withTrashed()->updateOrCreate(
                ['circle_id' => null, 'question_en' => $data['question_en']],
                array_merge($data, ['circle_id' => null, 'is_active' => true]),
            );
            if ($card->trashed()) {
                $card->restore();
            }
            $this->setAuditIfNew($card);
        }
    }

    /**
     * The weekly challenge for the Work Stress circle — a port of `challenge_data`.
     */
    private function seedWorkStressChallenge(): void
    {
        $circle = Circle::withTrashed()->where('name_en', 'Work Stress')->first();
        if ($circle === null) {
            return;
        }

        $challenge = CircleChallenge::withTrashed()->updateOrCreate(
            ['circle_id' => $circle->id, 'title_en' => 'We are in the same boat'],
            [
                'circle_id' => $circle->id,
                'title_ar' => 'احنا في نفس المركب 🚣',
                'title_en' => 'We are in the same boat',
                'description_ar' => 'نفصل الشغل ساعة قبل النوم، ٥ أيام. ماشيين سوا 💪',
                'description_en' => 'Disconnect from work an hour before bed, 5 days. Walking together.',
                'is_active' => true,
            ],
        );
        if ($challenge->trashed()) {
            $challenge->restore();
        }
        $this->setAuditIfNew($challenge);

        $steps = [
            ['text_ar' => 'قفلت الشغل ٨ مساءً', 'text_en' => 'Closed work at 8 PM', 'sort_order' => 1],
            ['text_ar' => 'مشيت ١٠ دقايق', 'text_en' => 'Walked for 10 minutes', 'sort_order' => 2],
            ['text_ar' => 'كتبت ٣ حاجات حلوة في يومي', 'text_en' => 'Wrote 3 good things about my day', 'sort_order' => 3],
            ['text_ar' => 'بطّلت موبايل قبل النوم بساعة', 'text_en' => 'No phone an hour before bed', 'sort_order' => 4],
            ['text_ar' => 'نمت بدري', 'text_en' => 'Slept early', 'sort_order' => 5],
        ];

        foreach ($steps as $data) {
            CircleChallengeStep::updateOrCreate(
                ['circle_challenge_id' => $challenge->id, 'sort_order' => $data['sort_order']],
                array_merge($data, ['circle_challenge_id' => $challenge->id]),
            );
        }
    }
}
