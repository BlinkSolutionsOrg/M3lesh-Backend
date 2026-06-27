<?php

namespace Database\Seeders;

use App\Models\Wheel\WheelChallenge;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class WheelSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedActiveChallenge();
    }

    /**
     * The one active wheel challenge — a port of the app's wheel mock.
     */
    private function seedActiveChallenge(): void
    {
        $challenge = WheelChallenge::withTrashed()->updateOrCreate(
            ['title_en' => 'Tell us the most embarrassing thing that happened to you'],
            [
                'segment_emoji' => '😂',
                'pill_label_ar' => 'هزار النهاردة',
                'pill_label_en' => 'Today\'s fun',
                'title_ar' => 'احكي أكتر موقف محرج حصلك',
                'title_en' => 'Tell us the most embarrassing thing that happened to you',
                'compose_hint_ar' => 'قول موقفك… ضحّكنا 😄',
                'compose_hint_en' => 'Share your moment… make us laugh',
                'room_banner_ar' => 'احكي أكتر موقف محرج حصلك',
                'room_banner_en' => 'Tell us the most embarrassing thing that happened to you',
                'color' => '#3D6B52',
                'bg_color' => '#EAF6EF',
                'is_active' => true,
            ],
        );
        if ($challenge->trashed()) {
            $challenge->restore();
        }
        $this->setAuditIfNew($challenge);
    }
}
