<?php

namespace App\Services\Post;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Repositories\Eloquent\PostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Service
{
    public function __construct(
        private PostRepository $repository
    ) {}

    /**
     * Создает новый пост
     *
     * @param array $data
     * @return Post
     * @throws \Exception
     */
    public function store(array $data): Post
    {
        $this->validate($data);

        try {
            DB::beginTransaction();

            $categoryId = $this->getCategoryIdWithUpdate($data['category'] ?? []);
            $tagIds = $this->getTagIdsWithUpdate($data['tags'] ?? []);

            $post = $this->repository->createWithRelations([
                'title' => $data['title'],
                'content' => $data['content'],
                'image' => $data['image'] ?? null,
                'category_id' => $categoryId,
                'tags' => $tagIds
            ]);

            DB::commit();
            return $post;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw new \Exception('Failed to create post', 0, $e);
        }
    }

    /**
     * Обновляет существующий пост
     *
     * @param Post $post
     * @param array $data
     * @return Post
     * @throws \Exception
     */
    public function update(Post $post, array $data): Post
    {
        $this->validate($data, $post);

        try {
            DB::beginTransaction();

            $updateData = [
                'title' => $data['title'],
                'content' => $data['content'],
                'image' => $data['image'] ?? null,
            ];

            if (isset($data['category'])) {
                $updateData['category_id'] = $this->getCategoryIdWithUpdate($data['category']);
            }

            $post = $this->repository->update($post, $updateData);

            if (isset($data['tags'])) {
                $tagIds = $this->getTagIdsWithUpdate($data['tags']);
                $post->tags()->sync($tagIds);
            }

            DB::commit();
            return $post->load('tags');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post update failed', [
                'error' => $e->getMessage(),
                'post_id' => $post->id,
                'data' => $data
            ]);
            throw new \Exception('Failed to update post', 0, $e);
        }
    }

    private function validate(array $data, ?Post $post = null): void
    {
        $rules = [
            'title' => 'required|string|min:1',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'category' => 'nullable|array',
            'category.id' => 'nullable|exists:categories,id',
            'category.title' => 'required_without:category.id|string|min:1',
            'tags' => 'nullable|array',
            'tags.*.id' => 'nullable|exists:tags,id',
            'tags.*.title' => 'required_without:tags.*.id|string|min:1'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Получает или создает теги
     *
     * @param array $tags
     * @return array
     */
    private function getTagIds(array $tags): array
    {
        $tagIds = [];
        foreach ($tags as $tag) {
            $tagModel = !isset($tag['id']) 
                ? Tag::create($tag) 
                : Tag::findOrFail($tag['id']);
            
            $tagIds[] = $tagModel->id;
        }

        return $tagIds;
    }

    /**
     * Получает или обновляет теги
     *
     * @param array $tags
     * @return array
     */
    private function getTagIdsWithUpdate(array $tags): array
    {
        if (empty($tags)) {
            return [];
        }

        $tagIds = [];
        foreach ($tags as $tag) {
            if (isset($tag['id'])) {
                $tagIds[] = $tag['id'];
            } else {
                $newTag = Tag::create(['title' => $tag['title']]);
                $tagIds[] = $newTag->id;
            }
        }

        return $tagIds;
    }

    /**
     * Получает или обновляет категорию
     *
     * @param array|null $item
     * @return int|null
     */
    private function getCategoryIdWithUpdate(?array $item): ?int
    {
        if (!$item) {
            return null;
        }

        if (!isset($item['id'])) {
            $category = Category::create($item);
        } else {
            $category = Category::findOrFail($item['id']);
            $category->update($item);
            $category = $category->fresh();
        }

        return $category->id;
    }

    /**
     * Получает или создает категорию
     *
     * @param array|null $item
     * @return int|null
     */
    private function getCategoryId(?array $item): ?int
    {
        if (!$item) {
            return null;
        }

        if (!isset($item['id'])) {
            $category = Category::create($item);
        } else {
            $category = Category::findOrFail($item['id']);
        }

        return $category->id;
    }
}
