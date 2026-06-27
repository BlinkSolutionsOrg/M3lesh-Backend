<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Http\Resources\Space\DailyCardTipResource;
use App\Models\Space\DailyCardDraw;
use App\Models\Space\DailyCardTip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyCardController extends Controller
{
    /**
     * Active daily-card tips catalog (read-only, optional auth).
     */
    public function tips(Request $request): JsonResponse
    {
        $items = DailyCardTip::query()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'data' => DailyCardTipResource::collection($items)->resolve($request),
        ]);
    }

    /**
     * The caller's draw for today, or { data: null } if they have not drawn yet.
     */
    public function today(Request $request): JsonResponse
    {
        $user = $request->user();

        $draw = DailyCardDraw::query()
            ->where('user_id', $user->id)
            ->whereDate('draw_date', now()->toDateString())
            ->with('tip')
            ->first();

        if ($draw === null || $draw->tip === null) {
            return response()->json(['data' => null]);
        }

        return DailyCardTipResource::make($draw->tip)->response($request);
    }

    /**
     * Draw the day's card. Idempotent per day via daily_card_draws unique(user_id, draw_date):
     * if already drawn today, return that tip; otherwise pick a random active tip and record it.
     */
    public function draw(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = now()->toDateString();

        $existing = DailyCardDraw::query()
            ->where('user_id', $user->id)
            ->whereDate('draw_date', $today)
            ->with('tip')
            ->first();

        if ($existing !== null && $existing->tip !== null) {
            return DailyCardTipResource::make($existing->tip)->response($request);
        }

        $tip = DailyCardTip::query()->active()->inRandomOrder()->first();
        if ($tip === null) {
            return response()->json(['data' => null]);
        }

        // firstOrCreate is idempotent under the unique(user_id, draw_date) constraint;
        // return the tip from the row that actually persisted (not the freshly-picked
        // one) so a racing/double-tap request still gets today's real card.
        $draw = DailyCardDraw::query()->firstOrCreate(
            ['user_id' => $user->id, 'draw_date' => $today],
            ['daily_card_tip_id' => $tip->id],
        );
        $draw->loadMissing('tip');

        return DailyCardTipResource::make($draw->tip ?? $tip)->response($request);
    }
}
