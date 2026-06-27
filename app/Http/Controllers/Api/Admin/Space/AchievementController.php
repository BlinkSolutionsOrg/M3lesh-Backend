<?php

namespace App\Http\Controllers\Api\Admin\Space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\AdminStoreAchievementRequest;
use App\Http\Requests\Space\AdminUpdateAchievementRequest;
use App\Http\Resources\Space\AdminAchievementResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Space\Achievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'created_at', 'updated_at', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Achievement::class);

        $query = Achievement::query()->withAudit();

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
                    ->orWhere('name_ar', 'like', '%'.$search.'%')
                    ->orWhere('key', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderBy('sort_order')->orderByDesc('created_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (Achievement $a) => AdminAchievementResource::make($a)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreAchievementRequest $request): JsonResponse
    {
        $this->authorize('create', Achievement::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $achievement = Achievement::create($validated);
        $achievement->loadAudit();

        return AdminAchievementResource::make($achievement)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, Achievement $admin_achievement): JsonResponse
    {
        $this->authorize('view', $admin_achievement);
        $admin_achievement->loadAudit();

        return AdminAchievementResource::make($admin_achievement)->response($request);
    }

    public function update(AdminUpdateAchievementRequest $request, Achievement $admin_achievement): JsonResponse
    {
        $this->authorize('update', $admin_achievement);

        $admin_achievement->update($request->validated());

        return AdminAchievementResource::make($admin_achievement->fresh()->loadAudit())->response($request);
    }

    public function destroy(Achievement $admin_achievement): JsonResponse
    {
        $this->authorize('delete', $admin_achievement);
        $admin_achievement->delete();

        return response()->json(null, 204);
    }

    public function restore(Achievement $admin_achievement): JsonResponse
    {
        $this->authorize('restore', $admin_achievement);
        $admin_achievement->restore();

        return AdminAchievementResource::make($admin_achievement->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(Achievement $admin_achievement): JsonResponse
    {
        $this->authorize('forceDelete', $admin_achievement);
        $admin_achievement->forceDelete();

        return response()->json(null, 204);
    }
}
