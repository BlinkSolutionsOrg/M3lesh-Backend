<?php

namespace App\Http\Controllers\Api\User\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Help\StoreHelpAskRequest;
use App\Http\Resources\Help\HelpAskResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Help\HelpAsk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HelpAskController extends Controller
{
    use ApiPaginationFilters;

    public function index(Request $request): JsonResponse
    {
        $query = HelpAsk::query()
            ->with(['user', 'circle'])
            ->orderByDesc('created_at');

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (HelpAsk $ask) => HelpAskResource::make($ask)->resolve($request));

        return response()->json($paginator);
    }

    public function show(Request $request, HelpAsk $ask): JsonResponse
    {
        Gate::forUser($request->user('sanctum'))->authorize('view', $ask);

        $ask->load(['user', 'circle']);

        return HelpAskResource::make($ask)->response($request);
    }

    public function store(StoreHelpAskRequest $request): JsonResponse
    {
        $this->authorize('create', HelpAsk::class);

        $validated = $request->validated();
        $ask = HelpAsk::create([
            'user_id' => $request->user()->id,
            'circle_id' => $validated['circle_id'] ?? null,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'status' => 'open',
            'last_activity_at' => now(),
        ]);

        $ask->load(['user', 'circle']);

        return HelpAskResource::make($ask)->response($request)->setStatusCode(201);
    }

    public function destroy(Request $request, HelpAsk $ask): JsonResponse
    {
        $this->authorize('delete', $ask);
        $ask->delete();

        return response()->json(null, 204);
    }
}
