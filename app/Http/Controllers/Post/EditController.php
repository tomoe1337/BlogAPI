<?php

namespace App\Http\Controllers\Post;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class EditController extends BaseController
{
    public function __invoke(Post $post): JsonResponse
    {
        $categories = Category::all();
        $tags = Tag::all();

        return response()->json([
            'post' => new PostResource($post),
            'categories' => CategoryResource::collection($categories),
            'tags' => TagResource::collection($tags)
        ]);
    }
}
