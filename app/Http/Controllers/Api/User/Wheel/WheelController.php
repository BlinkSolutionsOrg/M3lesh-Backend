<?php

namespace App\Http\Controllers\Api\User\Wheel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wheel\ReactWheelResponseRequest;
use App\Http\Requests\Wheel\StoreWheelResponseRequest;
use App\Http\Requests\Wheel\StoreWheelRoomMessageRequest;
use App\Http\Resources\Wheel\WheelChallengeResource;
use App\Http\Resources\Wheel\WheelResponseResource;
use App\Http\Resources\Wheel\WheelRoomMessageResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\User\User;
use App\Models\Wheel\WheelChallenge;
use App\Models\Wheel\WheelResponse;
use App\Models\Wheel\WheelResponseReaction;
use App\Models\Wheel\WheelRoomMessage;
use App\Models\Wheel\WheelSpin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WheelController extends Controller
{
    use ApiPaginationFilters;

    /**
     * The current active wheel challenge (latest active), or null.
     */
    private function activeChallenge(): ?WheelChallenge
    {
        return WheelChallenge::query()->active()->orderByDesc('created_at')->first();
    }

    public function today(Request $request): JsonResponse
    {
        $challenge = $this->activeChallenge();
        if ($challenge === null) {
            return response()->json(['data' => null]);
        }

        Gate::forUser($request->user('sanctum'))->authorize('view', $challenge);

        $user = $request->user('sanctum');
        if ($user instanceof User) {
            $challenge->loadCount(['spins as spun_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        return WheelChallengeResource::make($challenge)->response($request);
    }

    public function spin(Request $request): JsonResponse
    {
        $challenge = $this->activeChallenge();
        if ($challenge === null) {
            abort(404);
        }

        $this->authorize('spin', $challenge);

        $spin = WheelSpin::query()
            ->where('wheel_challenge_id', $challenge->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($spin === null) {
            WheelSpin::create([
                'wheel_challenge_id' => $challenge->id,
                'user_id' => $request->user()->id,
            ]);
            $challenge->increment('spins_count');
            $challenge->refresh();
        }

        return response()->json([
            'spun' => true,
            'spins_count' => $challenge->spins_count,
            'segment_emoji' => $challenge->segment_emoji,
        ]);
    }

    public function responsesIndex(Request $request): JsonResponse
    {
        $challenge = $this->activeChallenge();
        if ($challenge === null) {
            return response()->json(['data' => [], 'current_page' => 1, 'last_page' => 1, 'per_page' => $this->getPerPage($request), 'total' => 0]);
        }

        Gate::forUser($request->user('sanctum'))->authorize('view', $challenge);

        $user = $request->user('sanctum');

        $query = WheelResponse::query()
            ->forChallenge($challenge->id)
            ->with('user')
            ->orderByDesc('created_at');

        if ($user instanceof User) {
            $query->withCount([
                'reactions as laugh_reacted_by_me' => fn ($q) => $q
                    ->where('user_id', $user->id)
                    ->where('type', WheelResponseReaction::TYPE_LAUGH),
                'reactions as heart_reacted_by_me' => fn ($q) => $q
                    ->where('user_id', $user->id)
                    ->where('type', WheelResponseReaction::TYPE_HEART),
            ]);
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (WheelResponse $r) => WheelResponseResource::make($r)->resolve($request));

        return response()->json($paginator);
    }

    public function responsesStore(StoreWheelResponseRequest $request): JsonResponse
    {
        $challenge = $this->activeChallenge();
        if ($challenge === null) {
            abort(404);
        }

        $this->authorize('respond', $challenge);

        $validated = $request->validated();
        $response = WheelResponse::create([
            'wheel_challenge_id' => $challenge->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        $challenge->increment('responses_count');

        $response->load('user');
        $response->loadCount([
            'reactions as laugh_reacted_by_me' => fn ($q) => $q
                ->where('user_id', $request->user()->id)
                ->where('type', WheelResponseReaction::TYPE_LAUGH),
            'reactions as heart_reacted_by_me' => fn ($q) => $q
                ->where('user_id', $request->user()->id)
                ->where('type', WheelResponseReaction::TYPE_HEART),
        ]);

        return WheelResponseResource::make($response)->response($request)->setStatusCode(201);
    }

    public function react(ReactWheelResponseRequest $request, WheelResponse $response): JsonResponse
    {
        $this->authorize('react', $response);

        $type = $request->validated('type');
        $column = $type === WheelResponseReaction::TYPE_LAUGH ? 'laugh_count' : 'heart_count';

        $reaction = WheelResponseReaction::query()
            ->where('wheel_response_id', $response->id)
            ->where('user_id', $request->user()->id)
            ->where('type', $type)
            ->first();

        if ($reaction) {
            $reaction->delete();
            if ($response->{$column} > 0) {
                $response->decrement($column);
            }
        } else {
            WheelResponseReaction::create([
                'wheel_response_id' => $response->id,
                'user_id' => $request->user()->id,
                'type' => $type,
            ]);
            $response->increment($column);
        }

        $response->refresh();

        return response()->json([
            'type' => $type,
            'reacted' => $reaction === null,
            'laugh_count' => $response->laugh_count,
            'heart_count' => $response->heart_count,
        ]);
    }

    public function destroyResponse(Request $request, WheelResponse $response): JsonResponse
    {
        $this->authorize('delete', $response);
        $response->delete();

        return response()->json(null, 204);
    }

    public function roomMessagesIndex(Request $request): JsonResponse
    {
        $challenge = $this->activeChallenge();
        if ($challenge === null) {
            return response()->json(['data' => [], 'current_page' => 1, 'last_page' => 1, 'per_page' => $this->getPerPage($request), 'total' => 0]);
        }

        Gate::forUser($request->user('sanctum'))->authorize('view', $challenge);

        $paginator = WheelRoomMessage::query()
            ->forChallenge($challenge->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage($request));

        $paginator->through(fn (WheelRoomMessage $m) => WheelRoomMessageResource::make($m)->resolve($request));

        return response()->json($paginator);
    }

    public function roomMessagesStore(StoreWheelRoomMessageRequest $request): JsonResponse
    {
        $challenge = $this->activeChallenge();
        if ($challenge === null) {
            abort(404);
        }

        $this->authorize('message', $challenge);

        $message = WheelRoomMessage::create([
            'wheel_challenge_id' => $challenge->id,
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        $challenge->increment('room_messages_count');
        $message->load('user');

        return WheelRoomMessageResource::make($message)->response($request)->setStatusCode(201);
    }
}
