<?php

namespace App\Http\Controllers\Api\Admin\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\AdminStoreCommunitySeasonRequest;
use App\Http\Requests\Community\AdminUpdateCommunitySeasonRequest;
use App\Http\Resources\Community\AdminCommunitySeasonResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Community\CommunitySeason;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunitySeasonController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'created_at', 'updated_at', 'leaves_count', 'goal_leaves', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CommunitySeason::class);

        $query = CommunitySeason::query()->withAudit();

        if ($request->has('is_active')) {
            $active = $this->parseBool($request, 'is_active');
            if ($active !== null) {
                $query->where('is_active', $active);
            }
        }

        if ($this->parseBool($request, 'with_trashed') === true) {
            $query->withTrashed();
        } elseif ($this->parseBool($request, 'only_trashed') === true) {
            $query->onlyTrashed();
        }

        $search = $request->input('search') ?? $request->input('q');
        if (is_string($search) && $search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name_en', 'like', '%'.$search.'%')
                    ->orWhere('name_ar', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderByDesc('updated_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (CommunitySeason $season) => AdminCommunitySeasonResource::make($season)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreCommunitySeasonRequest $request): JsonResponse
    {
        $this->authorize('create', CommunitySeason::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $season = CommunitySeason::create($validated);
        $season->loadAudit();

        return AdminCommunitySeasonResource::make($season)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, CommunitySeason $admin_community_season): JsonResponse
    {
        $this->authorize('view', $admin_community_season);
        $admin_community_season->loadAudit();

        return AdminCommunitySeasonResource::make($admin_community_season)->response($request);
    }

    public function update(AdminUpdateCommunitySeasonRequest $request, CommunitySeason $admin_community_season): JsonResponse
    {
        $this->authorize('update', $admin_community_season);

        $admin_community_season->update($request->validated());

        return AdminCommunitySeasonResource::make($admin_community_season->fresh()->loadAudit())->response($request);
    }

    public function destroy(CommunitySeason $admin_community_season): JsonResponse
    {
        $this->authorize('delete', $admin_community_season);
        $admin_community_season->delete();

        return response()->json(null, 204);
    }

    public function restore(CommunitySeason $admin_community_season): JsonResponse
    {
        $this->authorize('restore', $admin_community_season);
        $admin_community_season->restore();

        return AdminCommunitySeasonResource::make($admin_community_season->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(CommunitySeason $admin_community_season): JsonResponse
    {
        $this->authorize('forceDelete', $admin_community_season);
        $admin_community_season->forceDelete();

        return response()->json(null, 204);
    }
}
