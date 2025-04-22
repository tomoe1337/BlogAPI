<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\JsonResponse;

class DestroyController extends BaseController
{
    public function __invoke(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
