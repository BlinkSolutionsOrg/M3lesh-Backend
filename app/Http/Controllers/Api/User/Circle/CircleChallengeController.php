<?php

namespace App\Http\Controllers\Api\User\Circle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\StoreCircleChallengeMessageRequest;
use App\Http\Resources\Circle\CircleChallengeMessageResource;
use App\Http\Resources\Circle\CircleChallengeResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Circle\Circle;
use App\Models\Circle\CircleChallenge;
use App\Models\Circle\CircleChallengeMessage;
use App\Models\Circle\CircleChallengeStep;
use App\Models\Circle\CircleChallengeStepCompletion;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CircleChallengeController extends Controller
{
    use ApiPaginationFilters;

    /**
     * The circle's current active challenge (latest active), or null.
     */
    private function activeChallenge(Circle $circle): ?CircleChallenge
    {
        return $circle->challenges()->where('is_active', true)->first();
    }

    /**
     * Reload a challenge with its steps + the caller's per-step completion flags.
     */
    private function loadForUser(CircleChallenge $challenge, ?User $user): CircleChallenge
    {
        $challenge->load(['steps' => function ($q) use ($user): void {
            $q->orderBy('sort_order');
            if ($user instanceof User) {
                $q->withCount(['completions as done_by_me' => fn ($c) => $c->where('user_id', $user->id)]);
            }
        }]);

        return $challenge;
    }

    public function show(Request $request, Circle $circle): JsonResponse
    {
        $challenge = $this->activeChallenge($circle);
        if ($challenge === null) {
            return response()->json(['data' => null]);
        }

        Gate::forUser($request->user('sanctum'))->authorize('view', $challenge);

        $this->loadForUser($challenge, $request->user('sanctum'));

        return CircleChallengeResource::make($challenge)->response($request);
    }

    public function toggleStep(Request $request, Circle $circle, CircleChallengeStep $step): JsonResponse
    {
        $challenge = $this->activeChallenge($circle);
        if ($challenge === null || $step->circle_challenge_id !== $challenge->id) {
            abort(404);
        }

        $this->authorize('completeStep', $challenge);

        $user = $request->user();
        $stepIds = $challenge->steps()->pluck('id');

        $before = CircleChallengeStepCompletion::query()
            ->whereIn('circle_challenge_step_id', $stepIds)
            ->where('user_id', $user->id)
            ->count();

        $completion = CircleChallengeStepCompletion::query()
            ->where('circle_challenge_step_id', $step->id)
            ->where('user_id', $user->id)
            ->first();

        if ($completion) {
            $completion->delete();
        } else {
            CircleChallengeStepCompletion::create([
                'circle_challenge_step_id' => $step->id,
                'user_id' => $user->id,
            ]);
        }

        $after = CircleChallengeStepCompletion::query()
            ->whereIn('circle_challenge_step_id', $stepIds)
            ->where('user_id', $user->id)
            ->count();

        // A "participant" is a member who completed at least one step.
        if ($before === 0 && $after > 0) {
            $challenge->increment('participants_count');
        } elseif ($before > 0 && $after === 0 && $challenge->participants_count > 0) {
            $challenge->decrement('participants_count');
        }

        $challenge->refresh();
        $this->loadForUser($challenge, $user);

        return CircleChallengeResource::make($challenge)->response($request);
    }

    public function messagesIndex(Request $request, Circle $circle): JsonResponse
    {
        $challenge = $this->activeChallenge($circle);
        if ($challenge === null) {
            return response()->json(['data' => [], 'current_page' => 1, 'last_page' => 1, 'per_page' => $this->getPerPage($request), 'total' => 0]);
        }

        Gate::forUser($request->user('sanctum'))->authorize('view', $challenge);

        $paginator = CircleChallengeMessage::query()
            ->forChallenge($challenge->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage($request));

        $paginator->through(fn (CircleChallengeMessage $m) => CircleChallengeMessageResource::make($m)->resolve($request));

        return response()->json($paginator);
    }

    public function messagesStore(StoreCircleChallengeMessageRequest $request, Circle $circle): JsonResponse
    {
        $challenge = $this->activeChallenge($circle);
        if ($challenge === null) {
            abort(404);
        }

        $this->authorize('postMessage', $challenge);

        $message = CircleChallengeMessage::create([
            'circle_challenge_id' => $challenge->id,
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        $circle->forceFill(['last_activity_at' => now()])->save();
        $message->load('user');

        return CircleChallengeMessageResource::make($message)->response($request)->setStatusCode(201);
    }
}
