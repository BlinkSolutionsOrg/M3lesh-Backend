<?php

namespace App\Http\Controllers\Api\Admin\Companion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Companion\AdminStoreCompanionReplyRequest;
use App\Http\Requests\Companion\AdminUpdateCompanionReplyRequest;
use App\Http\Resources\Companion\AdminCompanionReplyResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Companion\CompanionReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanionReplyController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'weight', 'created_at', 'updated_at', 'is_active', 'is_greeting'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CompanionReply::class);

        $query = CompanionReply::query()->withAudit();

        if ($request->has('is_active')) {
            $active = $this->parseBool($request, 'is_active');
            if ($active !== null) {
                $query->where('is_active', $active);
            }
        }

        if ($request->has('is_greeting')) {
            $greeting = $this->parseBool($request, 'is_greeting');
            if ($greeting !== null) {
                $query->where('is_greeting', $greeting);
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
            $query->orderByDesc('updated_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (CompanionReply $r) => AdminCompanionReplyResource::make($r)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreCompanionReplyRequest $request): JsonResponse
    {
        $this->authorize('create', CompanionReply::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_greeting'] = $validated['is_greeting'] ?? false;
        $validated['weight'] = $validated['weight'] ?? 1;

        $reply = CompanionReply::create($validated);
        $reply->loadAudit();

        return AdminCompanionReplyResource::make($reply)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, CompanionReply $admin_companion_reply): JsonResponse
    {
        $this->authorize('view', $admin_companion_reply);
        $admin_companion_reply->loadAudit();

        return AdminCompanionReplyResource::make($admin_companion_reply)->response($request);
    }

    public function update(AdminUpdateCompanionReplyRequest $request, CompanionReply $admin_companion_reply): JsonResponse
    {
        $this->authorize('update', $admin_companion_reply);

        $admin_companion_reply->update($request->validated());

        return AdminCompanionReplyResource::make($admin_companion_reply->fresh()->loadAudit())->response($request);
    }

    public function destroy(CompanionReply $admin_companion_reply): JsonResponse
    {
        $this->authorize('delete', $admin_companion_reply);
        $admin_companion_reply->delete();

        return response()->json(null, 204);
    }

    public function restore(CompanionReply $admin_companion_reply): JsonResponse
    {
        $this->authorize('restore', $admin_companion_reply);
        $admin_companion_reply->restore();

        return AdminCompanionReplyResource::make($admin_companion_reply->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(CompanionReply $admin_companion_reply): JsonResponse
    {
        $this->authorize('forceDelete', $admin_companion_reply);
        $admin_companion_reply->forceDelete();

        return response()->json(null, 204);
    }
}
