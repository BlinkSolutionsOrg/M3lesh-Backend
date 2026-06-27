<?php

namespace Database\Seeders;

use App\Models\Circle\Circle;
use App\Models\Help\HelpAsk;
use App\Models\Help\HelpReply;
use Database\Seeders\Concerns\SeedsWithSuperAdmin;
use Illuminate\Database\Seeder;

class HelpSeeder extends Seeder
{
    use SeedsWithSuperAdmin;

    /** Author for all seeded asks/replies (the first user from UserSeeder). */
    private const USER_ID = 1;

    public function run(): void
    {
        $this->seedAsks();
    }

    /**
     * The four asks on the «محتاج رأيكم» list — a direct port of the app's `kAsks`,
     * plus a few replies for the first ask (ported from `kHelpReplies`).
     */
    private function seedAsks(): void
    {
        $asks = [
            [
                'title' => 'قلقي قبل النوم بيمنعني أنام…',
                'body' => 'بقالي كذا أسبوع كل ما أنام دماغي بتفضل تلف على كل اللي عايز أعمله بكرة، وباجي الصبح مرهق. حد عدّى من ده وعرف يهدّي دماغه إزاي؟ محتاج نصايحكم أو تجاربكم 🤍',
                'circle_en' => 'Anxiety',
                'is_anonymous' => false,
                'replies' => [
                    [
                        'type' => 'experience',
                        'is_anonymous' => false,
                        'votes' => 32,
                        'body' => 'عدّيت بنفس اللي إنت فيه السنة اللي فاتت. اللي ساعدني إني قسّمت اليوم لمهام صغيرة، وكل ما أخلّص حاجة أحطّ علامة. الإحساس بالإنجاز ده رجّعلي طاقتي بالراحة 🤍',
                    ],
                    [
                        'type' => 'advice',
                        'is_anonymous' => false,
                        'votes' => 48,
                        'body' => 'جرّب تكتب اللي بيقلقك في ورقة قبل النوم وتسيبها للصبح. بتفضّي دماغك وبتنام أحسن.',
                    ],
                    [
                        'type' => 'experience',
                        'is_anonymous' => true,
                        'votes' => 19,
                        'body' => 'أنا كمان مريت بده وكان تقيل جداً. كلّمت دكتور وفرق معايا، وملقتش في ده أي حرج. متستناش لحد ما توصل للقاع.',
                    ],
                    [
                        'type' => 'advice',
                        'is_anonymous' => false,
                        'votes' => 7,
                        'body' => 'متستعجلش على نفسك… الموضوع بياخد وقت، وإنت بتعمل اللي عليك. معلش 🤍',
                    ],
                ],
            ],
            [
                'title' => 'إزاي أبطّل أفكّر في حد مكمّلش معايا؟',
                'body' => 'كل حاجة بتفكّرني بيه وتعبت… محتاج أعرف أعمل إيه عشان أعدّي المرحلة دي.',
                'circle_en' => 'Breakup',
                'is_anonymous' => true,
                'replies' => [],
            ],
            [
                'title' => 'أسيب شغل بكرهه ولا أستحمّل؟',
                'body' => 'الشغل بياكل صحتي بس خايفة من المجهول… حد جرّب قرار زي ده؟',
                'circle_en' => 'Work Stress',
                'is_anonymous' => false,
                'replies' => [],
            ],
            [
                'title' => 'أهلي مش فاهمين تعبي النفسي، أعمل إيه؟',
                'body' => 'كل ما أحكي يقولولي اتغطّى واتعدّى… محتاج طريقة أوصّلهم بيها.',
                'circle_en' => 'Family Problems',
                'is_anonymous' => true,
                'replies' => [],
            ],
        ];

        foreach ($asks as $data) {
            $circleId = null;
            if (isset($data['circle_en'])) {
                $circle = Circle::withTrashed()->where('name_en', $data['circle_en'])->first();
                $circleId = $circle?->id;
            }

            $ask = HelpAsk::withTrashed()->updateOrCreate(
                ['user_id' => self::USER_ID, 'title' => $data['title']],
                [
                    'user_id' => self::USER_ID,
                    'circle_id' => $circleId,
                    'title' => $data['title'],
                    'body' => $data['body'],
                    'is_anonymous' => $data['is_anonymous'],
                    'status' => 'open',
                    'replies_count' => count($data['replies']),
                    'last_activity_at' => now(),
                ],
            );
            if ($ask->trashed()) {
                $ask->restore();
            }
            $this->setAuditIfNew($ask);

            foreach ($data['replies'] as $replyData) {
                $reply = HelpReply::withTrashed()->updateOrCreate(
                    ['help_ask_id' => $ask->id, 'user_id' => self::USER_ID, 'body' => $replyData['body']],
                    [
                        'help_ask_id' => $ask->id,
                        'user_id' => self::USER_ID,
                        'type' => $replyData['type'],
                        'body' => $replyData['body'],
                        'is_anonymous' => $replyData['is_anonymous'],
                        'votes_count' => $replyData['votes'],
                    ],
                );
                if ($reply->trashed()) {
                    $reply->restore();
                }
                $this->setAuditIfNew($reply);
            }
        }
    }
}
