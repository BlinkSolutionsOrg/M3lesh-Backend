<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\StoreFutureLetterRequest;
use App\Http\Resources\Space\FutureLetterResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Space\FutureLetter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FutureLetterController extends Controller
{
    use ApiPaginationFilters;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $paginator = FutureLetter::query()
            ->forUser($user->id)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage($request));

        $paginator->through(fn (FutureLetter $letter) => FutureLetterResource::make($letter)->resolve($request));

        return response()->json($paginator);
    }

    public function show(Request $request, FutureLetter $letter): JsonResponse
    {
        $this->authorize('view', $letter);

        return FutureLetterResource::make($letter)->response($request);
    }

    public function store(StoreFutureLetterRequest $request): JsonResponse
    {
        $this->authorize('create', FutureLetter::class);

        $validated = $request->validated();
        $letter = FutureLetter::create([
            'user_id' => $request->user()->id,
            'recipient_label' => $validated['recipient_label'],
            'body' => $validated['body'],
            'unlock_at' => $validated['unlock_at'],
            'bg_color' => $validated['bg_color'] ?? null,
            'text_color' => $validated['text_color'] ?? null,
        ]);

        return FutureLetterResource::make($letter)->response($request)->setStatusCode(201);
    }

    public function open(Request $request, FutureLetter $letter): JsonResponse
    {
        $this->authorize('open', $letter);

        if (! $letter->isLocked() && $letter->opened_at === null) {
            $letter->forceFill(['opened_at' => now()])->save();
        }

        $letter->refresh();

        return FutureLetterResource::make($letter)->response($request);
    }

    public function destroy(Request $request, FutureLetter $letter): JsonResponse
    {
        $this->authorize('delete', $letter);
        $letter->delete();

        return response()->json(null, 204);
    }
}
