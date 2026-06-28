<?php

namespace Database\Seeders;

use App\Models\Circle\Circle;
use App\Models\Circle\CircleChallenge;
use App\Models\Circle\CircleChallengeStep;
use App\Models\Post\PostCommentPreset;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

/**
 * Idempotent starter content so the app feels alive:
 *  - «معلش» ready comment replies (post presets).
 *  - An active weekly challenge + steps for every circle that has none.
 * (Avatars are SVGs uploaded by the admin, so they're not seeded here.)
 */
class MaaleshStarterContentSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedCommentPresets();
        $this->seedCircleChallenges();
    }

    private function seedCommentPresets(): void
    {
        $presets = [
            'أنا معاك 🤍',
            'معلش… كله هيعدّي',
            'حسّيت بيك فعلاً',
            'إنت مش لوحدك',
            'شكراً إنك حكيت 🌷',
            'خد وقتك، إحنا مستنينك',
            'قوي إنك قلت اللي جواك 💪',
            'ربنا معاك ويقوّيك',
        ];

        foreach ($presets as $text) {
            $preset = PostCommentPreset::query()->firstOrNew(['text' => $text]);
            $preset->is_active = true;
            $this->setAuditIfNew($preset);
            $preset->save();
        }
    }

    private function seedCircleChallenges(): void
    {
        $steps = [
            ['ar' => 'اكتب حاجة وحدة حلوة حصلتلك النهاردة', 'en' => 'Write one nice thing that happened today'],
            ['ar' => 'خد ٥ دقايق تتنفّس بهدوء', 'en' => 'Take 5 quiet minutes to breathe'],
            ['ar' => 'ابعت اطمئنان لحد بتحبه', 'en' => 'Check in on someone you love'],
            ['ar' => 'اعمل حاجة صغيّرة بتفرّحك', 'en' => 'Do one small thing that makes you happy'],
            ['ar' => 'قول لنفسك جملة لطيفة قبل النوم', 'en' => 'Say something kind to yourself before bed'],
        ];

        Circle::query()->where('is_active', true)->get()->each(
            function (Circle $circle) use ($steps): void {
                $hasActive = CircleChallenge::query()
                    ->where('circle_id', $circle->id)
                    ->where('is_active', true)
                    ->exists();
                if ($hasActive) {
                    return;
                }

                $challenge = new CircleChallenge([
                    'circle_id' => $circle->id,
                    'title_ar' => 'تحدّي الأسبوع 🌱',
                    'title_en' => 'Challenge of the week 🌱',
                    'description_ar' => 'خطوة صغيّرة كل يوم… بنكبر بيها سوا 🤍',
                    'description_en' => 'A small step each day — we grow together.',
                    'is_active' => true,
                ]);
                $challenge->save();

                foreach ($steps as $i => $step) {
                    CircleChallengeStep::query()->create([
                        'circle_challenge_id' => $challenge->id,
                        'text_ar' => $step['ar'],
                        'text_en' => $step['en'],
                        'sort_order' => $i + 1,
                    ]);
                }
            },
        );
    }
}
