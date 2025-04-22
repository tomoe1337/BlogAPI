<?php

namespace App\Http\Controllers\Post;

use App\Http\Requests\Post\UpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();

        $post = $this->service->update($post, $data);

        return response()->json(new PostResource($post));
    }
}
