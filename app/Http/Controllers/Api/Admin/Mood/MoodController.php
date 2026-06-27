<?php

namespace App\Http\Controllers\Api\Admin\Mood;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mood\AdminStoreMoodRequest;
use App\Http\Requests\Mood\AdminUpdateMoodRequest;
use App\Http\Resources\Mood\AdminMoodResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Mood\Mood;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'created_at', 'updated_at', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Mood::class);

        $query = Mood::query()->withAudit();

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
                $q->where('label_en', 'like', '%'.$search.'%')
                    ->orWhere('label_ar', 'like', '%'.$search.'%')
                    ->orWhere('key', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderBy('sort_order');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (Mood $mood) => AdminMoodResource::make($mood)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreMoodRequest $request): JsonResponse
    {
        $this->authorize('create', Mood::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $mood = Mood::create($validated);
        $mood->loadAudit();

        return AdminMoodResource::make($mood)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, Mood $admin_mood): JsonResponse
    {
        $this->authorize('view', $admin_mood);
        $admin_mood->loadAudit();

        return AdminMoodResource::make($admin_mood)->response($request);
    }

    public function update(AdminUpdateMoodRequest $request, Mood $admin_mood): JsonResponse
    {
        $this->authorize('update', $admin_mood);

        $admin_mood->update($request->validated());

        return AdminMoodResource::make($admin_mood->fresh()->loadAudit())->response($request);
    }

    public function destroy(Mood $admin_mood): JsonResponse
    {
        $this->authorize('delete', $admin_mood);
        $admin_mood->delete();

        return response()->json(null, 204);
    }

    public function restore(Mood $admin_mood): JsonResponse
    {
        $this->authorize('restore', $admin_mood);
        $admin_mood->restore();

        return AdminMoodResource::make($admin_mood->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(Mood $admin_mood): JsonResponse
    {
        $this->authorize('forceDelete', $admin_mood);
        $admin_mood->forceDelete();

        return response()->json(null, 204);
    }
}
