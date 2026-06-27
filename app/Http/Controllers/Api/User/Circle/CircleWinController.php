<?php

namespace App\Http\Controllers\Api\User\Circle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\StoreCircleWinRequest;
use App\Http\Resources\Circle\CircleWinResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Circle\Circle;
use App\Models\Circle\CircleWin;
use App\Models\Circle\CircleWinCheer;
use App\Models\User\User;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CircleWinController extends Controller
{
    use ApiPaginationFilters;

    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function index(Request $request, Circle $circle): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = CircleWin::query()
            ->forCircle($circle->id)
            ->with('user')
            ->orderByDesc('created_at');

        if ($user instanceof User) {
            $query->withCount(['cheers as cheered_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (CircleWin $win) => CircleWinResource::make($win)->resolve($request));

        return response()->json($paginator);
    }

    public function store(StoreCircleWinRequest $request, Circle $circle): JsonResponse
    {
        $this->authorize('create', [CircleWin::class, $circle]);

        $validated = $request->validated();
        $win = CircleWin::create([
            'circle_id' => $circle->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        $circle->forceFill(['last_activity_at' => now()])->save();

        $win->load('user');
        $win->loadCount(['cheers as cheered_by_me' => fn ($q) => $q->where('user_id', $request->user()->id)]);

        return CircleWinResource::make($win)->response($request)->setStatusCode(201);
    }

    public function cheer(Request $request, Circle $circle, CircleWin $win): JsonResponse
    {
        if ($win->circle_id !== $circle->id) {
            abort(404);
        }

        $this->authorize('cheer', $win);

        $cheer = CircleWinCheer::query()
            ->where('circle_win_id', $win->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($cheer) {
            $cheer->delete();
            $win->decrement('cheers_count');
        } else {
            CircleWinCheer::create([
                'circle_win_id' => $win->id,
                'user_id' => $request->user()->id,
            ]);
            $win->increment('cheers_count');

            $actor = $request->user();
            if ($win->user_id !== $actor->id) {
                $owner = User::query()->find($win->user_id);
                if ($owner !== null) {
                    $this->notificationService->notify($owner, [
                        'title' => __('notifications.circle_win_cheered_title'),
                        'body' => __('notifications.circle_win_cheered_body', ['name' => $actor->name]),
                        'target_type' => 'circles',
                        'target_id' => $circle->id,
                    ]);
                }
            }
        }

        $win->refresh();

        return response()->json([
            'cheered' => $cheer === null,
            'cheers_count' => $win->cheers_count,
        ]);
    }

    public function destroy(Request $request, Circle $circle, CircleWin $win): JsonResponse
    {
        if ($win->circle_id !== $circle->id) {
            abort(404);
        }

        $this->authorize('delete', $win);
        $win->delete();

        return response()->json(null, 204);
    }
}
