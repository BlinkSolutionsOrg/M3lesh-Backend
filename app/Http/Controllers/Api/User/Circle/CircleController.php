<?php

namespace App\Http\Controllers\Api\User\Circle;

use App\Http\Controllers\Controller;
use App\Http\Resources\Circle\CircleResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Circle\Circle;
use App\Models\Circle\CircleMember;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CircleController extends Controller
{
    use ApiPaginationFilters;

    private const SORT_ALLOWED = ['id', 'sort_order', 'created_at', 'updated_at', 'members_count'];

    public function index(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = Circle::query()->active();

        if ($user instanceof User) {
            $query->withCount(['members as joined_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        $query = $this->applySort($query, $request, self::SORT_ALLOWED);
        if (! $request->has('sort_by')) {
            $query->ordered();
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (Circle $circle) => CircleResource::make($circle)->resolve($request));

        return response()->json($paginator);
    }

    public function show(Request $request, Circle $circle): JsonResponse
    {
        Gate::forUser($request->user('sanctum'))->authorize('view', $circle);

        $user = $request->user('sanctum');
        if ($user instanceof User) {
            $circle->loadCount(['members as joined_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        return CircleResource::make($circle)->response($request);
    }

    public function join(Request $request, Circle $circle): JsonResponse
    {
        $this->authorize('join', $circle);

        $member = CircleMember::query()
            ->where('circle_id', $circle->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($member === null) {
            CircleMember::create([
                'circle_id' => $circle->id,
                'user_id' => $request->user()->id,
            ]);
            $circle->increment('members_count');
            $circle->refresh();
        }

        return response()->json([
            'joined' => true,
            'members_count' => $circle->members_count,
        ]);
    }

    public function leave(Request $request, Circle $circle): JsonResponse
    {
        $this->authorize('leave', $circle);

        $member = CircleMember::query()
            ->where('circle_id', $circle->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($member !== null) {
            $member->delete();
            if ($circle->members_count > 0) {
                $circle->decrement('members_count');
            }
            $circle->refresh();
        }

        return response()->json([
            'joined' => false,
            'members_count' => $circle->members_count,
        ]);
    }
}
