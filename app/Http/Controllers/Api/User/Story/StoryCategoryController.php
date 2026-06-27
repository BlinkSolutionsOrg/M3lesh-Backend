<?php

namespace App\Http\Controllers\Api\User\Story;

use App\Http\Controllers\Controller;
use App\Http\Resources\Story\StoryCategoryResource;
use App\Models\Story\StoryCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoryCategoryController extends Controller
{
    /**
     * Active story categories, ordered.
     * Returned as a plain list — the chip row is small and cycled client-side.
     */
    public function index(Request $request): JsonResponse
    {
        $items = StoryCategory::query()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'data' => StoryCategoryResource::collection($items)->resolve($request),
        ]);
    }
}
