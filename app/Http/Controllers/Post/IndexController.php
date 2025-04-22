<?php

namespace App\Http\Controllers\Post;

use App\Http\Filters\PostFilter;
use App\Http\Requests\Post\FilterRequest;
use App\Http\Resources\Post\PostResource;
use App\Repositories\Eloquent\PostRepository;
use Illuminate\Http\JsonResponse;

class IndexController extends BaseController
{
    public function __construct(
        private readonly PostRepository $postRepository
    ) {
    }

    public function __invoke(FilterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $page = $data['page'] ?? 1;
        $perPage = $data['perPage'] ?? 10;

        $filter = app()->make(PostFilter::class, ['queryParams' => array_filter($data)]);

        $posts = $this->postRepository->getPaginated($perPage, $page);

        $postsData = PostResource::collection($posts->items())->toArray($request);

        return response()->json([
            'data' => $postsData,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total()
            ]
        ]);
    }
}

                