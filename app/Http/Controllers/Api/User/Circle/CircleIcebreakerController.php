<?php

namespace App\Http\Controllers\Api\User\Circle;

use App\Http\Controllers\Controller;
use App\Http\Resources\Circle\CircleIcebreakerResource;
use App\Models\Circle\Circle;
use App\Models\Circle\CircleIcebreaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CircleIcebreakerController extends Controller
{
    /**
     * Active icebreaker cards for a circle (its own + global cards), ordered.
     * Returned as a plain list — the deck is small and cycled client-side.
     */
    public function index(Request $request, Circle $circle): JsonResponse
    {
        $items = CircleIcebreaker::query()
            ->active()
            ->where(fn ($q) => $q->where('circle_id', $circle->id)->orWhereNull('circle_id'))
            ->ordered()
            ->get();

        return response()->json([
            'data' => CircleIcebreakerResource::collection($items)->resolve($request),
        ]);
    }
}
