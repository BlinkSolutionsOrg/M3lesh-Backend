<?php

namespace App\Http\Controllers\Api\Admin\Space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\AdminStoreDailyCardTipRequest;
use App\Http\Requests\Space\AdminUpdateDailyCardTipRequest;
use App\Http\Resources\Space\AdminDailyCardTipResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Space\DailyCardTip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyCardTipController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'created_at', 'updated_at', 'is_active'];

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', DailyCardTip::class);

        $query = DailyCardTip::query()->withAudit();

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
            $query->orderBy('sort_order')->orderByDesc('created_at');
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (DailyCardTip $tip) => AdminDailyCardTipResource::make($tip)->resolve($request));

        return response()->json($paginator);
    }

    public function store(AdminStoreDailyCardTipRequest $request): JsonResponse
    {
        $this->authorize('create', DailyCardTip::class);

        $validated = $request->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $tip = DailyCardTip::create($validated);
        $tip->loadAudit();

        return AdminDailyCardTipResource::make($tip)->response($request)->setStatusCode(201);
    }

    public function show(Request $request, DailyCardTip $admin_daily_card_tip): JsonResponse
    {
        $this->authorize('view', $admin_daily_card_tip);
        $admin_daily_card_tip->loadAudit();

        return AdminDailyCardTipResource::make($admin_daily_card_tip)->response($request);
    }

    public function update(AdminUpdateDailyCardTipRequest $request, DailyCardTip $admin_daily_card_tip): JsonResponse
    {
        $this->authorize('update', $admin_daily_card_tip);

        $admin_daily_card_tip->update($request->validated());

        return AdminDailyCardTipResource::make($admin_daily_card_tip->fresh()->loadAudit())->response($request);
    }

    public function destroy(DailyCardTip $admin_daily_card_tip): JsonResponse
    {
        $this->authorize('delete', $admin_daily_card_tip);
        $admin_daily_card_tip->delete();

        return response()->json(null, 204);
    }

    public function restore(DailyCardTip $admin_daily_card_tip): JsonResponse
    {
        $this->authorize('restore', $admin_daily_card_tip);
        $admin_daily_card_tip->restore();

        return AdminDailyCardTipResource::make($admin_daily_card_tip->fresh()->loadAudit())->response(request());
    }

    public function forceDestroy(DailyCardTip $admin_daily_card_tip): JsonResponse
    {
        $this->authorize('forceDelete', $admin_daily_card_tip);
        $admin_daily_card_tip->forceDelete();

        return response()->json(null, 204);
    }
}
