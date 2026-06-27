<?php

namespace Database\Seeders;

use App\Models\Story\StoryCategory;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $this->seedCategories();
    }

    /**
     * The four story filter chips — a direct port of the app's `kStoryTabs`.
     */
    private function seedCategories(): void
    {
        $categories = [
            [
                'key' => 'all',
                'label_ar' => 'الكل',
                'label_en' => 'All',
                'color' => '#FFFFFF',
                'bg_color' => '#6E5C86',
                'border_color' => '#6E5C86',
                'reaction_emoji' => '💜',
                'reaction_label_ar' => 'معلش',
                'reaction_label_en' => 'Maalesh',
                'sort_order' => 1,
            ],
            [
                'key' => 'sweet',
                'label_ar' => 'لحظات حلوة',
                'label_en' => 'Sweet moments',
                'color' => '#9C7A2E',
                'bg_color' => '#FFFFFF',
                'border_color' => '#F0E0BE',
                'reaction_emoji' => '🎉',
                'reaction_label_ar' => 'مبروك',
                'reaction_label_en' => 'Congrats',
                'sort_order' => 2,
            ],
            [
                'key' => 'vent',
                'label_ar' => 'فضفضة',
                'label_en' => 'Venting',
                'color' => '#9C5A4E',
                'bg_color' => '#FFFFFF',
                'border_color' => '#F3D6CE',
                'reaction_emoji' => '🫂',
                'reaction_label_ar' => 'سامعينك',
                'reaction_label_en' => 'We hear you',
                'sort_order' => 3,
            ],
            [
                'key' => 'wins',
                'label_ar' => 'مكاسب',
                'label_en' => 'Wins',
                'color' => '#3D6B52',
                'bg_color' => '#FFFFFF',
                'border_color' => '#CFE8D8',
                'reaction_emoji' => '🎉',
                'reaction_label_ar' => 'مبروك',
                'reaction_label_en' => 'Congrats',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $data) {
            $category = StoryCategory::withTrashed()->updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['is_active' => true]),
            );
            if ($category->trashed()) {
                $category->restore();
            }
            $this->setAuditIfNew($category);
        }
    }
}
