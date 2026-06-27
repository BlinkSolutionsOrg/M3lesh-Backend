<?php

namespace App\Http\Controllers\Api\Admin\Companion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Companion\AdminStoreCompanionSuggestionRequest;
use App\Http\Requests\Companion\AdminUpdateCompanionSuggestionRequest;
use App\Http\Resources\Companion\AdminCompanionSuggestionResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Companion\CompanionSuggestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanionSuggestionController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'created_at', 'updated_at', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CompanionSuggestion::class);

        $query = CompanionSuggestion::query()->withAudit();

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
                $q->where('text_en', 'like', '%'.$search.'%')
                    ->orWhere('text_ar', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderBy('sort_order');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (CompanionSuggestion $s) => AdminCompanionSuggestionResource::make($s)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreCompanionSuggestionRequest $request): JsonResponse
    {
        $this->authorize('create', CompanionSuggestion::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $suggestion = CompanionSuggestion::create($validated);
        $suggestion->loadAudit();

        return AdminCompanionSuggestionResource::make($suggestion)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, CompanionSuggestion $admin_companion_suggestion): JsonResponse
    {
        $this->authorize('view', $admin_companion_suggestion);
        $admin_companion_suggestion->loadAudit();

        return AdminCompanionSuggestionResource::make($admin_companion_suggestion)->response($request);
    }

    public function update(AdminUpdateCompanionSuggestionRequest $request, CompanionSuggestion $admin_companion_suggestion): JsonResponse
    {
        $this->authorize('update', $admin_companion_suggestion);

        $admin_companion_suggestion->update($request->validated());

        return AdminCompanionSuggestionResource::make($admin_companion_suggestion->fresh()->loadAudit())->response($request);
    }

    public function destroy(CompanionSuggestion $admin_companion_suggestion): JsonResponse
    {
        $this->authorize('delete', $admin_companion_suggestion);
        $admin_companion_suggestion->delete();

        return response()->json(null, 204);
    }

    public function restore(CompanionSuggestion $admin_companion_suggestion): JsonResponse
    {
        $this->authorize('restore', $admin_companion_suggestion);
        $admin_companion_suggestion->restore();

        return AdminCompanionSuggestionResource::make($admin_companion_suggestion->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(CompanionSuggestion $admin_companion_suggestion): JsonResponse
    {
        $this->authorize('forceDelete', $admin_companion_suggestion);
        $admin_companion_suggestion->forceDelete();

        return response()->json(null, 204);
    }
}
