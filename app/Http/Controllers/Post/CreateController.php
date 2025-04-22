<?php

namespace App\Http\Controllers\Post;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class CreateController extends BaseController
{
    public function __invoke(): JsonResponse
    {
        $categories = Category::all();
        $tags = Tag::all();

        return response()->json([
            'categories' => CategoryResource::collection($categories),
            'tags' => TagResource::collection($tags)
        ]);
    }
}
