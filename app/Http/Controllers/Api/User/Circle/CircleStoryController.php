<?php

namespace App\Http\Controllers\Api\User\Circle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\StoreCircleStoryRequest;
use App\Http\Resources\Circle\CircleStoryResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Circle\Circle;
use App\Models\Circle\CircleStory;
use App\Models\Circle\CircleStoryHeart;
use App\Models\User\User;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CircleStoryController extends Controller
{
    use ApiPaginationFilters;

    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function index(Request $request, Circle $circle): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = CircleStory::query()
            ->forCircle($circle->id)
            ->with('user')
            ->orderByDesc('created_at');

        if ($user instanceof User) {
            $query->withCount(['hearts as hearted_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (CircleStory $story) => CircleStoryResource::make($story)->resolve($request));

        return response()->json($paginator);
    }

    public function store(StoreCircleStoryRequest $request, Circle $circle): JsonResponse
    {
        $this->authorize('create', [CircleStory::class, $circle]);

        $validated = $request->validated();
        $story = CircleStory::create([
            'circle_id' => $circle->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        $circle->forceFill(['last_activity_at' => now()])->save();

        $story->load('user');
        $story->loadCount(['hearts as hearted_by_me' => fn ($q) => $q->where('user_id', $request->user()->id)]);

        return CircleStoryResource::make($story)->response($request)->setStatusCode(201);
    }

    public function heart(Request $request, Circle $circle, CircleStory $story): JsonResponse
    {
        if ($story->circle_id !== $circle->id) {
            abort(404);
        }

        $this->authorize('heart', $story);

        $heart = CircleStoryHeart::query()
            ->where('circle_story_id', $story->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($heart) {
            $heart->delete();
            $story->decrement('hearts_count');
        } else {
            CircleStoryHeart::create([
                'circle_story_id' => $story->id,
                'user_id' => $request->user()->id,
            ]);
            $story->increment('hearts_count');

            $actor = $request->user();
            if ($story->user_id !== $actor->id) {
                $owner = User::query()->find($story->user_id);
                if ($owner !== null) {
                    $this->notificationService->notify($owner, [
                        'title' => __('notifications.circle_story_hearted_title'),
                        'body' => __('notifications.circle_story_hearted_body', ['name' => $actor->name]),
                        'target_type' => 'circles',
                        'target_id' => $circle->id,
                    ]);
                }
            }
        }

        $story->refresh();

        return response()->json([
            'hearted' => $heart === null,
            'hearts_count' => $story->hearts_count,
        ]);
    }

    public function destroy(Request $request, Circle $circle, CircleStory $story): JsonResponse
    {
        if ($story->circle_id !== $circle->id) {
            abort(404);
        }

        $this->authorize('delete', $story);
        $story->delete();

        return response()->json(null, 204);
    }
}
