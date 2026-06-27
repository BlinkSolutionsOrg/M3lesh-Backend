<?php

namespace App\Http\Controllers\Api\User\Space;

use App\Http\Controllers\Controller;
use App\Http\Resources\Space\RoomDecorationResource;
use App\Models\Space\RoomDecoration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomDecorationController extends Controller
{
    /**
     * Active room decorations catalog (read-only, optional auth).
     */
    public function index(Request $request): JsonResponse
    {
        $items = RoomDecoration::query()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'data' => RoomDecorationResource::collection($items)->resolve($request),
        ]);
    }
}
