<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\StoreGratitudeNoteRequest;
use App\Http\Resources\Space\GratitudeNoteResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Space\GratitudeNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GratitudeNoteController extends Controller
{
    use ApiPaginationFilters;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $paginator = GratitudeNote::query()
            ->forUser($user->id)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage($request));

        $paginator->through(fn (GratitudeNote $note) => GratitudeNoteResource::make($note)->resolve($request));

        return response()->json($paginator);
    }

    public function store(StoreGratitudeNoteRequest $request): JsonResponse
    {
        $this->authorize('create', GratitudeNote::class);

        $validated = $request->validated();
        $note = GratitudeNote::create([
            'user_id' => $request->user()->id,
            'text' => $validated['text'],
            'color' => $validated['color'] ?? null,
            'rotation' => $validated['rotation'] ?? null,
        ]);

        return GratitudeNoteResource::make($note)->response($request)->setStatusCode(201);
    }

    public function destroy(Request $request, GratitudeNote $note): JsonResponse
    {
        $this->authorize('delete', $note);
        $note->delete();

        return response()->json(null, 204);
    }
}
