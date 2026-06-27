<?php

namespace App\Http\Controllers\Api\Admin\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Help\AdminStoreHelpAskRequest;
use App\Http\Requests\Help\AdminUpdateHelpAskRequest;
use App\Http\Resources\Help\AdminHelpAskResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Help\HelpAsk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HelpAskController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'created_at', 'updated_at', 'replies_count', 'last_activity_at'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', HelpAsk::class);

        $query = HelpAsk::query()->with(['user', 'circle'])->withAudit();

        if ($request->has('status')) {
            $status = $request->input('status');
            if (is_string($status) && $status !== '') {
                $query->where('status', $status);
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
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('body', 'like', '%'.$search.'%');
            });
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->orderByDesc('created_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (HelpAsk $ask) => AdminHelpAskResource::make($ask)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreHelpAskRequest $request): JsonResponse
    {
        $this->authorize('create', HelpAsk::class);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['status'] = $validated['status'] ?? 'open';
        $validated['last_activity_at'] = now();

        $ask = HelpAsk::create($validated);
        $ask->load(['user', 'circle'])->loadAudit();

        return AdminHelpAskResource::make($ask)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, HelpAsk $admin_help_ask): JsonResponse
    {
        $this->authorize('view', $admin_help_ask);
        $admin_help_ask->load(['user', 'circle'])->loadAudit();

        return AdminHelpAskResource::make($admin_help_ask)->response($request);
    }

    public function update(AdminUpdateHelpAskRequest $request, HelpAsk $admin_help_ask): JsonResponse
    {
        $this->authorize('update', $admin_help_ask);

        $admin_help_ask->update($request->validated());

        return AdminHelpAskResource::make($admin_help_ask->fresh()->load(['user', 'circle'])->loadAudit())->response($request);
    }

    public function destroy(HelpAsk $admin_help_ask): JsonResponse
    {
        $this->authorize('delete', $admin_help_ask);
        $admin_help_ask->delete();

        return response()->json(null, 204);
    }

    public function restore(HelpAsk $admin_help_ask): JsonResponse
    {
        $this->authorize('restore', $admin_help_ask);
        $admin_help_ask->restore();

        return AdminHelpAskResource::make($admin_help_ask->fresh()->load(['user', 'circle'])->loadAudit())->response(request());
    }

    public function forceDestroy(HelpAsk $admin_help_ask): JsonResponse
    {
        $this->authorize('forceDelete', $admin_help_ask);
        $admin_help_ask->forceDelete();

        return response()->json(null, 204);
    }
}
