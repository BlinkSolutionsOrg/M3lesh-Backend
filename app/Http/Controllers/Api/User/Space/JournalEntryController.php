<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\StoreJournalEntryRequest;
use App\Http\Resources\Space\JournalEntryResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Space\JournalEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    use ApiPaginationFilters;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $paginator = JournalEntry::query()
            ->forUser($user->id)
            ->orderByDesc('entry_date')
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage($request));

        $paginator->through(fn (JournalEntry $entry) => JournalEntryResource::make($entry)->resolve($request));

        return response()->json($paginator);
    }

    public function store(StoreJournalEntryRequest $request): JsonResponse
    {
        $this->authorize('create', JournalEntry::class);

        $validated = $request->validated();
        $entry = JournalEntry::create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'mood' => $validated['mood'] ?? null,
            'entry_date' => now()->toDateString(),
        ]);

        return JournalEntryResource::make($entry)->response($request)->setStatusCode(201);
    }

    public function destroy(Request $request, JournalEntry $entry): JsonResponse
    {
        $this->authorize('delete', $entry);
        $entry->delete();

        return response()->json(null, 204);
    }
}
