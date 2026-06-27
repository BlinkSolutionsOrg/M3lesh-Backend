<?php

namespace Database\Seeders;

use App\Models\Companion\CompanionReply;
use App\Models\Companion\CompanionSetting;
use App\Models\Companion\CompanionSuggestion;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class CompanionSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedReplies();
        $this->seedSuggestions();
        $this->seedSettings();
    }

    /**
     * The cat reply pool — the greeting plus the four canned replies from MaaleshCubit._replies.
     */
    private function seedReplies(): void
    {
        $replies = [
            [
                'text_ar' => 'أهلاً بيك يا صاحبي 🌙 أنا هنا أسمعك من غير حكم ولا نصيحة. قولّي اللي في قلبك على راحتك.',
                'text_en' => 'Welcome, my friend 🌙 I am here to listen without judgement. Tell me what is on your heart.',
                'is_greeting' => true,
                'weight' => 1,
            ],
            [
                'text_ar' => 'سمعتك يا صاحبي 🤍 وكل اللي بتحسّه طبيعي. عايز تحكيلي أكتر؟',
                'text_en' => 'I heard you, my friend 🤍 everything you feel is valid. Want to tell me more?',
                'is_greeting' => false,
                'weight' => 1,
            ],
            [
                'text_ar' => 'معلش… خد نفس عميق معايا. إحنا في الموضوع ده سوا.',
                'text_en' => 'It is okay… take a deep breath with me. We are in this together.',
                'is_greeting' => false,
                'weight' => 1,
            ],
            [
                'text_ar' => 'تسلم إنك قلت اللي جواك. ده مش ضعف، ده شجاعة.',
                'text_en' => 'Thank you for saying what is inside you. That is not weakness, that is courage.',
                'is_greeting' => false,
                'weight' => 1,
            ],
            [
                'text_ar' => 'حاسس إن الموضوع تقيل عليك. تحب نجرّب تمرين تنفّس صغير؟',
                'text_en' => 'It sounds heavy on you. Want to try a small breathing exercise?',
                'is_greeting' => false,
                'weight' => 1,
            ],
        ];

        foreach ($replies as $data) {
            $reply = CompanionReply::withTrashed()->updateOrCreate(
                ['text_en' => $data['text_en']],
                array_merge($data, ['is_active' => true]),
            );
            if ($reply->trashed()) {
                $reply->restore();
            }
            $this->setAuditIfNew($reply);
        }
    }

    /**
     * The four suggestion chips from the companion screen.
     */
    private function seedSuggestions(): void
    {
        $chips = [
            ['text_ar' => 'محتاج أفضفض', 'text_en' => 'I need to vent', 'sort_order' => 1],
            ['text_ar' => 'نفسي أتهدّى', 'text_en' => 'I want to calm down', 'sort_order' => 2],
            ['text_ar' => 'حاسس بضغط', 'text_en' => 'I feel stressed', 'sort_order' => 3],
            ['text_ar' => 'مبسوط النهاردة', 'text_en' => 'I am happy today', 'sort_order' => 4],
        ];

        foreach ($chips as $data) {
            $chip = CompanionSuggestion::withTrashed()->updateOrCreate(
                ['text_en' => $data['text_en']],
                array_merge($data, ['is_active' => true]),
            );
            if ($chip->trashed()) {
                $chip->restore();
            }
            $this->setAuditIfNew($chip);
        }
    }

    /**
     * The singleton settings row (help banner + hotline + presence).
     */
    private function seedSettings(): void
    {
        $settings = CompanionSetting::query()->first() ?? new CompanionSetting;

        $settings->fill([
            'help_banner_title_ar' => 'لو محتاج مساعدة حقيقية دلوقتي',
            'help_banner_title_en' => 'If you need real help right now',
            'help_banner_body_ar' => 'الخط الساخن للصحة النفسية ١٦٣٢٨ — مجاني، ٢٤ ساعة. ده دعم بين ناس، مش بديل عن دكتور.',
            'help_banner_body_en' => 'Mental health hotline 16328 — free, 24 hours. This is peer support, not a substitute for a doctor.',
            'hotline_number' => '١٦٣٢٨',
            'presence_label_ar' => 'بيسمعك دلوقتي',
            'presence_label_en' => 'Listening now',
        ])->save();
    }
}
