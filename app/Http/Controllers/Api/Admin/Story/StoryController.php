<?php

namespace App\Http\Controllers\Api\Admin\Story;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\AdminUpdateStoryRequest;
use App\Http\Resources\Story\AdminStoryResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Story\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'created_at', 'updated_at', 'hearts_count', 'comments_count'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Story::class);

        $query = Story::query()->with(['user', 'category', 'circle'])->withAudit();

        if ($request->has('story_category_id')) {
            $query->where('story_category_id', $request->input('story_category_id'));
        }

        if ($request->has('circle_id')) {
            $query->where('circle_id', $request->input('circle_id'));
        }

        if ($this->parseBool($request, 'with_trashed') === true) {
            $query->withTrashed();
        } elseif ($this->parseBool($request, 'only_trashed') === true) {
            $query->onlyTrashed();
        }

        $search = $request->input('search') ?? $request->input('q');
        if (is_string($search) && $search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('body', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderByDesc('created_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (Story $story) => AdminStoryResource::make($story)->resolve($request));

        return response()->json($paginator);
    }

    public function show(Request $request, Story $admin_story): JsonResponse
    {
        $this->authorize('view', $admin_story);
        $admin_story->load(['user', 'category', 'circle'])->loadAudit();

        return AdminStoryResource::make($admin_story)->response($request);
    }

    public function update(AdminUpdateStoryRequest $request, Story $admin_story): JsonResponse
    {
        $this->authorize('update', $admin_story);

        $admin_story->update($request->validated());

        return AdminStoryResource::make($admin_story->fresh(['user', 'category', 'circle'])->loadAudit())->response($request);
    }

    public function destroy(Story $admin_story): JsonResponse
    {
        $this->authorize('delete', $admin_story);
        $admin_story->delete();

        return response()->json(null, 204);
    }

    public function restore(Story $admin_story): JsonResponse
    {
        $this->authorize('restore', $admin_story);
        $admin_story->restore();

        return AdminStoryResource::make($admin_story->fresh(['user', 'category', 'circle'])->loadAudit())->response(request());
    }

    public function forceDestroy(Story $admin_story): JsonResponse
    {
        $this->authorize('forceDelete', $admin_story);
        $admin_story->forceDelete();

        return response()->json(null, 204);
    }
}
