<?php

namespace App\Http\Controllers\Api\Admin\Wheel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wheel\AdminStoreWheelChallengeRequest;
use App\Http\Requests\Wheel\AdminUpdateWheelChallengeRequest;
use App\Http\Resources\Wheel\AdminWheelChallengeResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Wheel\WheelChallenge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WheelChallengeController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'created_at', 'updated_at', 'spins_count', 'responses_count', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', WheelChallenge::class);

        $query = WheelChallenge::query()->withAudit();

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
                    ->orWhere('title_ar', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderByDesc('updated_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (WheelChallenge $challenge) => AdminWheelChallengeResource::make($challenge)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreWheelChallengeRequest $request): JsonResponse
    {
        $this->authorize('create', WheelChallenge::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $challenge = WheelChallenge::create($validated);
        $challenge->loadAudit();

        return AdminWheelChallengeResource::make($challenge)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, WheelChallenge $admin_wheel_challenge): JsonResponse
    {
        $this->authorize('view', $admin_wheel_challenge);
        $admin_wheel_challenge->loadAudit();

        return AdminWheelChallengeResource::make($admin_wheel_challenge)->response($request);
    }

    public function update(AdminUpdateWheelChallengeRequest $request, WheelChallenge $admin_wheel_challenge): JsonResponse
    {
        $this->authorize('update', $admin_wheel_challenge);

        $admin_wheel_challenge->update($request->validated());

        return AdminWheelChallengeResource::make($admin_wheel_challenge->fresh()->loadAudit())->response($request);
    }

    public function destroy(WheelChallenge $admin_wheel_challenge): JsonResponse
    {
        $this->authorize('delete', $admin_wheel_challenge);
        $admin_wheel_challenge->delete();

        return response()->json(null, 204);
    }

    public function restore(WheelChallenge $admin_wheel_challenge): JsonResponse
    {
        $this->authorize('restore', $admin_wheel_challenge);
        $admin_wheel_challenge->restore();

        return AdminWheelChallengeResource::make($admin_wheel_challenge->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(WheelChallenge $admin_wheel_challenge): JsonResponse
    {
        $this->authorize('forceDelete', $admin_wheel_challenge);
        $admin_wheel_challenge->forceDelete();

        return response()->json(null, 204);
    }
}
