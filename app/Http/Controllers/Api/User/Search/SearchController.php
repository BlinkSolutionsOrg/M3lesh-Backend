<?php

namespace App\Http\Controllers\Api\User\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\Circle\CircleResource;
use App\Http\Resources\Circle\CircleStoryResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Circle\Circle;
use App\Models\Circle\CircleStory;
use App\Models\Post\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Unified search across circles, posts and stories (optional auth).
 */
class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'data' => ['circles' => [], 'posts' => [], 'stories' => []],
            ]);
        }

        // Escape LIKE wildcards in the user term.
        $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $q).'%';

        $circles = Circle::query()
            ->where('is_active', true)
            ->where(function ($w) use ($like) {
                $w->where('name_ar', 'like', $like)
                    ->orWhere('name_en', 'like', $like)
                    ->orWhere('description_ar', 'like', $like)
                    ->orWhere('description_en', 'like', $like);
            })
            ->limit(8)
            ->get();

        $posts = Post::query()
            ->where('is_active', true)
            ->with('user')
            ->where('body', 'like', $like)
            ->latest('id')
            ->limit(12)
            ->get();

        $stories = CircleStory::query()
            ->with('user')
            ->where('body', 'like', $like)
            ->latest('id')
            ->limit(12)
            ->get();

        return response()->json([
            'data' => [
                'circles' => CircleResource::collection($circles)->resolve($request),
                'posts' => PostResource::collection($posts)->resolve($request),
                'stories' => CircleStoryResource::collection($stories)->resolve($request),
            ],
        ]);
    }
}
