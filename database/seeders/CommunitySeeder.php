<?php

namespace Database\Seeders;

use App\Models\Circle\Circle;
use App\Models\Community\CommunityMilestone;
use App\Models\Community\CommunitySeason;
use App\Models\Community\SupportLeaf;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    public function run(): void
    {
        $season = $this->seedSeason();
        $this->seedMilestones($season);
        $this->seedSupportLeaves($season);
    }

    /**
     * One active season — a port of the app's `communitySeasonName`.
     */
    private function seedSeason(): CommunitySeason
    {
        $season = CommunitySeason::withTrashed()->updateOrCreate(
            ['name_en' => 'Maalesh Spring'],
            [
                'name_ar' => 'ربيع معلش 🌸',
                'name_en' => 'Maalesh Spring',
                'goal_leaves' => 100000,
                'leaves_count' => 84230,
                'is_active' => true,
                'starts_at' => now()->subDays(30),
            ],
        );
        if ($season->trashed()) {
            $season->restore();
        }
        $this->setAuditIfNew($season);

        return $season;
    }

    /**
     * Reward milestones — a port of the app's `communityMilestones`.
     */
    private function seedMilestones(CommunitySeason $season): void
    {
        $milestones = [
            ['threshold' => 25000, 'label_ar' => 'فتحنا ستيكر القطة النايمة', 'label_en' => 'Unlocked the sleeping cat sticker', 'reward_type' => 'sticker', 'sort_order' => 1],
            ['threshold' => 50000, 'label_ar' => 'فتحنا خلفية الغروب', 'label_en' => 'Unlocked the sunset background', 'reward_type' => 'background', 'sort_order' => 2],
            ['threshold' => 100000, 'label_ar' => 'شجرة الموسم الكبيرة', 'label_en' => 'The big seasonal tree', 'reward_type' => 'tree', 'sort_order' => 3],
        ];

        foreach ($milestones as $data) {
            $milestone = CommunityMilestone::withTrashed()->updateOrCreate(
                ['community_season_id' => $season->id, 'threshold' => $data['threshold']],
                array_merge($data, ['community_season_id' => $season->id, 'is_active' => true]),
            );
            if ($milestone->trashed()) {
                $milestone->restore();
            }
            $this->setAuditIfNew($milestone);
        }
    }

    /**
     * A handful of sample support leaves so the leaderboard/feed/ticker have
     * content. Attached to user id 1 and a couple of seeded circles.
     */
    private function seedSupportLeaves(CommunitySeason $season): void
    {
        // Already seeded? Keep idempotent — only seed when empty.
        if (SupportLeaf::query()->where('community_season_id', $season->id)->exists()) {
            return;
        }

        $anxiety = Circle::withTrashed()->where('name_en', 'Anxiety')->first();
        $workStress = Circle::withTrashed()->where('name_en', 'Work Stress')->first();
        $breakup = Circle::withTrashed()->where('name_en', 'Breakup')->first();

        $rows = [
            ['circle' => $anxiety, 'action_type' => 'advice', 'minutes' => 0],
            ['circle' => $breakup, 'action_type' => 'support', 'minutes' => 1],
            ['circle' => $workStress, 'action_type' => 'advice', 'minutes' => 2],
            ['circle' => $anxiety, 'action_type' => 'win', 'minutes' => 4],
            ['circle' => $anxiety, 'action_type' => 'checkin', 'minutes' => 30],
            ['circle' => $workStress, 'action_type' => 'kind_word', 'minutes' => 90],
            ['circle' => $breakup, 'action_type' => 'support', 'minutes' => 1500],
        ];

        foreach ($rows as $row) {
            SupportLeaf::create([
                'user_id' => 1,
                'community_season_id' => $season->id,
                'circle_id' => $row['circle']?->id,
                'action_type' => $row['action_type'],
                'created_at' => now()->subMinutes($row['minutes']),
                'updated_at' => now()->subMinutes($row['minutes']),
            ]);
        }
    }
}
