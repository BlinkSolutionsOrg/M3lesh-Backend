<?php

namespace Database\Seeders;

use App\Models\Space\Achievement;
use App\Models\Space\DailyCardTip;
use App\Models\Space\RoomDecoration;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedDailyCardTips();
        $this->seedAchievements();
        $this->seedRoomDecorations();
    }

    /**
     * The daily-card tips — a direct port of the app's `dailyCardTips`.
     */
    private function seedDailyCardTips(): void
    {
        $tips = [
            ['text_ar' => 'النهاردة، كلّم حد بتحبّه من غير سبب 🤍', 'text_en' => 'Today, reach out to someone you love for no reason.', 'sort_order' => 1],
            ['text_ar' => 'خد ٣ أنفاس عميقة قبل ما تبدأ يومك.', 'text_en' => 'Take 3 deep breaths before you start your day.', 'sort_order' => 2],
            ['text_ar' => 'اكتب حاجة واحدة فرّحتك إمبارح.', 'text_en' => 'Write one thing that made you happy yesterday.', 'sort_order' => 3],
            ['text_ar' => 'متبقاش قاسي على نفسك… إنت بتحاول، وده يكفي.', 'text_en' => 'Be gentle with yourself — you are trying, and that is enough.', 'sort_order' => 4],
            ['text_ar' => 'امشي ١٠ دقايق من غير موبايل النهاردة.', 'text_en' => 'Walk for 10 minutes without your phone today.', 'sort_order' => 5],
        ];

        foreach ($tips as $i => $data) {
            $tip = DailyCardTip::withTrashed()->updateOrCreate(
                ['text_en' => $data['text_en']],
                array_merge($data, ['is_active' => true]),
            );
            if ($tip->trashed()) {
                $tip->restore();
            }
            $this->setAuditIfNew($tip);
        }
    }

    /**
     * The eight medals — a direct port of the app's `medals`.
     */
    private function seedAchievements(): void
    {
        $medals = [
            ['key' => 'first_step', 'icon' => '🌱', 'name_ar' => 'أول خطوة', 'name_en' => 'First Step', 'criterion_ar' => 'أول تشييك-إن', 'criterion_en' => 'First check-in', 'sort_order' => 1],
            ['key' => 'full_week', 'icon' => '🔥', 'name_ar' => 'أسبوع كامل', 'name_en' => 'Full Week', 'criterion_ar' => '٧ أيام ستريك', 'criterion_en' => '7-day streak', 'sort_order' => 2],
            ['key' => 'supporter', 'icon' => '🤍', 'name_ar' => 'سند', 'name_en' => 'Supporter', 'criterion_ar' => 'ساندت ١٠ ناس', 'criterion_en' => 'Supported 10 people', 'sort_order' => 3],
            ['key' => 'mentor', 'icon' => '🧭', 'name_ar' => 'دليل', 'name_en' => 'Guide', 'criterion_ar' => 'بادج Mentor', 'criterion_en' => 'Mentor badge', 'sort_order' => 4],
            ['key' => 'gardener', 'icon' => '🪴', 'name_ar' => 'بستاني', 'name_en' => 'Gardener', 'criterion_ar' => '٣٠ يوم متتالي', 'criterion_en' => '30 days in a row', 'sort_order' => 5],
            ['key' => 'calm', 'icon' => '🌙', 'name_ar' => 'هدوء', 'name_en' => 'Calm', 'criterion_ar' => '١٠ تمارين تنفّس', 'criterion_en' => '10 breathing exercises', 'sort_order' => 6],
            ['key' => 'storyteller', 'icon' => '📓', 'name_ar' => 'حكّاء', 'name_en' => 'Storyteller', 'criterion_ar' => '٢٠ يومية', 'criterion_en' => '20 journal entries', 'sort_order' => 7],
            ['key' => 'kindness_star', 'icon' => '⭐', 'name_ar' => 'نجمة الطيبة', 'name_en' => 'Kindness Star', 'criterion_ar' => '٥٠٠ نقطة', 'criterion_en' => '500 points', 'sort_order' => 8],
        ];

        foreach ($medals as $data) {
            $achievement = Achievement::withTrashed()->updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['is_active' => true]),
            );
            if ($achievement->trashed()) {
                $achievement->restore();
            }
            $this->setAuditIfNew($achievement);
        }
    }

    /**
     * The room decorations — a direct port of the app's `decorations`.
     */
    private function seedRoomDecorations(): void
    {
        $decor = [
            ['key' => 'window', 'title_ar' => 'شبّاك', 'title_en' => 'Window', 'required_level' => 1, 'sort_order' => 1],
            ['key' => 'plant', 'title_ar' => 'نبتة', 'title_en' => 'Plant', 'required_level' => 1, 'sort_order' => 2],
            ['key' => 'rug', 'title_ar' => 'سجادة', 'title_en' => 'Rug', 'required_level' => 2, 'sort_order' => 3],
            ['key' => 'warm_lamp', 'title_ar' => 'لمبة دافية', 'title_en' => 'Warm Lamp', 'required_level' => 3, 'sort_order' => 4],
            ['key' => 'bookshelf', 'title_ar' => 'مكتبة', 'title_en' => 'Bookshelf', 'required_level' => 4, 'sort_order' => 5],
            ['key' => 'aquarium', 'title_ar' => 'أكواريوم', 'title_en' => 'Aquarium', 'required_level' => 5, 'sort_order' => 6],
        ];

        foreach ($decor as $data) {
            $decoration = RoomDecoration::withTrashed()->updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['is_active' => true]),
            );
            if ($decoration->trashed()) {
                $decoration->restore();
            }
            $this->setAuditIfNew($decoration);
        }
    }
}
