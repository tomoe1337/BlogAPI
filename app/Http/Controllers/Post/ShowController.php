<?php

namespace App\Http\Controllers\Post;

use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class ShowController extends BaseController
{
    public function __invoke(Post $post): JsonResponse
    {
        return response()->json([
            'data' => new PostResource($post)
        ]);
    }
}
