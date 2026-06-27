<?php

namespace App\Http\Controllers\Api\Admin\Space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\AdminStoreRoomDecorationRequest;
use App\Http\Requests\Space\AdminUpdateRoomDecorationRequest;
use App\Http\Resources\Space\AdminRoomDecorationResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Space\RoomDecoration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomDecorationController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'required_level', 'created_at', 'updated_at', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', RoomDecoration::class);

        $query = RoomDecoration::query()->withAudit();

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
                $q->where('title_en', 'like', '%'.$search.'%')
                    ->orWhere('title_ar', 'like', '%'.$search.'%')
                    ->orWhere('key', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderBy('sort_order')->orderByDesc('created_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (RoomDecoration $d) => AdminRoomDecorationResource::make($d)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreRoomDecorationRequest $request): JsonResponse
    {
        $this->authorize('create', RoomDecoration::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $decoration = RoomDecoration::create($validated);
        $decoration->loadAudit();

        return AdminRoomDecorationResource::make($decoration)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, RoomDecoration $admin_room_decoration): JsonResponse
    {
        $this->authorize('view', $admin_room_decoration);
        $admin_room_decoration->loadAudit();

        return AdminRoomDecorationResource::make($admin_room_decoration)->response($request);
    }

    public function update(AdminUpdateRoomDecorationRequest $request, RoomDecoration $admin_room_decoration): JsonResponse
    {
        $this->authorize('update', $admin_room_decoration);

        $admin_room_decoration->update($request->validated());

        return AdminRoomDecorationResource::make($admin_room_decoration->fresh()->loadAudit())->response($request);
    }

    public function destroy(RoomDecoration $admin_room_decoration): JsonResponse
    {
        $this->authorize('delete', $admin_room_decoration);
        $admin_room_decoration->delete();

        return response()->json(null, 204);
    }

    public function restore(RoomDecoration $admin_room_decoration): JsonResponse
    {
        $this->authorize('restore', $admin_room_decoration);
        $admin_room_decoration->restore();

        return AdminRoomDecorationResource::make($admin_room_decoration->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(RoomDecoration $admin_room_decoration): JsonResponse
    {
        $this->authorize('forceDelete', $admin_room_decoration);
        $admin_room_decoration->forceDelete();

        return response()->json(null, 204);
    }
}
