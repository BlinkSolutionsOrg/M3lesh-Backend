<?php

namespace App\Http\Controllers\Api\User\Story;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\StoreStoryRequest;
use App\Http\Resources\Story\StoryResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Story\Story;
use App\Models\Story\StoryCategory;
use App\Models\Story\StoryHeart;
use App\Models\User\User;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoryController extends Controller
{
    use ApiPaginationFilters;

    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = Story::query()
            ->with(['user', 'category', 'circle'])
            ->orderByDesc('created_at');

        if ($request->filled('category_id')) {
            $query->forCategory((int) $request->input('category_id'));
        }

        if ($request->filled('category_key')) {
            $category = StoryCategory::query()->where('key', $request->input('category_key'))->first();
            if ($category !== null) {
                $query->forCategory($category->id);
            }
        }

        if ($request->filled('circle_id')) {
            $query->forCircle((int) $request->input('circle_id'));
        }

        if ($user instanceof User) {
            $query->withCount(['hearts as hearted_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (Story $story) => StoryResource::make($story)->resolve($request));

        return response()->json($paginator);
    }

    public function show(Request $request, Story $story): JsonResponse
    {
        Gate::forUser($request->user('sanctum'))->authorize('view', $story);

        $user = $request->user('sanctum');
        $story->load(['user', 'category', 'circle']);
        if ($user instanceof User) {
            $story->loadCount(['hearts as hearted_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        return StoryResource::make($story)->response($request);
    }

    public function store(StoreStoryRequest $request): JsonResponse
    {
        $this->authorize('create', Story::class);

        $validated = $request->validated();

        $categoryId = $validated['category_id'] ?? null;
        if ($categoryId === null && ! empty($validated['category_key'])) {
            $category = StoryCategory::query()->where('key', $validated['category_key'])->first();
            $categoryId = $category?->id;
        }

        $story = Story::create([
            'user_id' => $request->user()->id,
            'story_category_id' => $categoryId,
            'circle_id' => $validated['circle_id'] ?? null,
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'last_activity_at' => now(),
        ]);

        $story->load(['user', 'category', 'circle']);
        $story->loadCount(['hearts as hearted_by_me' => fn ($q) => $q->where('user_id', $request->user()->id)]);

        return StoryResource::make($story)->response($request)->setStatusCode(201);
    }

    public function heart(Request $request, Story $story): JsonResponse
    {
        $this->authorize('heart', $story);

        $heart = StoryHeart::query()
            ->where('story_id', $story->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($heart) {
            $heart->delete();
            $story->decrement('hearts_count');
        } else {
            StoryHeart::create([
                'story_id' => $story->id,
                'user_id' => $request->user()->id,
            ]);
            $story->increment('hearts_count');

            $actor = $request->user();
            if ($story->user_id !== $actor->id) {
                $owner = User::query()->find($story->user_id);
                if ($owner !== null) {
                    $this->notificationService->notify($owner, [
                        'title' => __('notifications.story_hearted_title'),
                        'body' => __('notifications.story_hearted_body', ['name' => $actor->name]),
                        'target_type' => 'stories',
                        'target_id' => $story->id,
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

    public function destroy(Request $request, Story $story): JsonResponse
    {
        $this->authorize('delete', $story);
        $story->delete();

        return response()->json(null, 204);
    }
}
