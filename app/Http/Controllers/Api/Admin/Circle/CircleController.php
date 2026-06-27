<?php

namespace App\Http\Controllers\Api\Admin\Circle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\AdminStoreCircleRequest;
use App\Http\Requests\Circle\AdminUpdateCircleRequest;
use App\Http\Resources\Circle\AdminCircleResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Circle\Circle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CircleController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'created_at', 'updated_at', 'members_count', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Circle::class);

        $query = Circle::query()->withAudit();

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
        $paginator->through(fn (Circle $circle) => AdminCircleResource::make($circle)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreCircleRequest $request): JsonResponse
    {
        $this->authorize('create', Circle::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $circle = Circle::create($validated);
        $circle->loadAudit();

        return AdminCircleResource::make($circle)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, Circle $admin_circle): JsonResponse
    {
        $this->authorize('view', $admin_circle);
        $admin_circle->loadAudit();

        return AdminCircleResource::make($admin_circle)->response($request);
    }

    public function update(AdminUpdateCircleRequest $request, Circle $admin_circle): JsonResponse
    {
        $this->authorize('update', $admin_circle);

        $admin_circle->update($request->validated());

        return AdminCircleResource::make($admin_circle->fresh()->loadAudit())->response($request);
    }

    public function destroy(Circle $admin_circle): JsonResponse
    {
        $this->authorize('delete', $admin_circle);
        $admin_circle->delete();

        return response()->json(null, 204);
    }

    public function restore(Circle $admin_circle): JsonResponse
    {
        $this->authorize('restore', $admin_circle);
        $admin_circle->restore();

        return AdminCircleResource::make($admin_circle->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(Circle $admin_circle): JsonResponse
    {
        $this->authorize('forceDelete', $admin_circle);
        $admin_circle->forceDelete();

        return response()->json(null, 204);
    }
}
