<?php

namespace App\Repositories\Eloquent;

use App\Contracts\CacheInterface;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected const CACHE_TTL = 3600; // 1 час
    protected const CACHE_PREFIX = 'posts:';

    public function __construct(
        Post $model,
        private readonly CacheInterface $cache
    ) {
        parent::__construct($model);
    }

    /**
     * Создать пост с тегами и категорией
     */
    public function createWithRelations(array $data): Post
    {
        // Удаляем теги из данных перед созданием поста
        $postData = array_diff_key($data, ['tags' => null]);
        $post = $this->model->create($postData);
        
        if (isset($data['tags'])) {
            $post->tags()->attach($data['tags']);
        }
        
        $this->invalidateCache();
        
        return $post->load(['category', 'tags']);
    }

    /**
     * Обновить пост с тегами и категорией
     */
    public function updateWithRelations(Post $post, array $data): Post
    {
        $post->update($data);
        
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        $this->invalidateCache();
        
        return $post->load(['category', 'tags']);
    }

    /**
     * Получить посты с отношениями
     */
    public function getWithRelations(): Collection
    {
        $cacheKey = self::CACHE_PREFIX . 'all';
        
        if ($this->cache->has($cacheKey)) {
            return collect($this->cache->get($cacheKey));
        }
        
        $posts = $this->model->with(['category', 'tags'])->get();
        $this->cache->put($cacheKey, $posts->toArray(), self::CACHE_TTL);
        
        return $posts;
    }

    public function getPaginated(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = self::CACHE_PREFIX . "page:{$page}:perPage:{$perPage}";
        $totalKey = self::CACHE_PREFIX . "total"; // Общий ключ для total
        
        if ($this->cache->has($cacheKey) && $this->cache->has($totalKey)) {
            $cachedData = $this->cache->get($cacheKey);
            $total = $this->cache->get($totalKey);
            
            // Преобразуем массив обратно в модели
            $posts = collect($cachedData)->map(function ($item) {
                $post = new Post();
                $post->id = $item['id'];
                $post->title = $item['title'];
                $post->content = $item['content'];
                $post->image = $item['image'];
                
                if (isset($item['category'])) {
                    $category = new \App\Models\Category();
                    $category->id = $item['category']['id'];
                    $category->title = $item['category']['title'];
                    $post->setRelation('category', $category);
                }
                
                if (isset($item['tags'])) {
                    $tags = collect($item['tags'])->map(function ($tag) {
                        $tagModel = new \App\Models\Tag();
                        $tagModel->id = $tag['id'];
                        $tagModel->title = $tag['title'];
                        return $tagModel;
                    });
                    $post->setRelation('tags', $tags);
                }
                
                return $post;
            });
            
            return new LengthAwarePaginator(
                $posts,
                $total,
                $perPage,
                $page
            );
        }
        
        $query = $this->model->with(['category', 'tags']);
        $total = $query->count();
        $posts = $query->forPage($page, $perPage)->get();
        
        // Подготавливаем данные для кеширования
        $cacheData = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'image' => $post->image,
                'category' => $post->category ? [
                    'id' => $post->category->id,
                    'title' => $post->category->title
                ] : null,
                'tags' => $post->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'title' => $tag->title
                    ];
                })->toArray()
            ];
        })->toArray();
        
        $this->cache->put($cacheKey, $cacheData, self::CACHE_TTL);
        $this->cache->put($totalKey, $total, self::CACHE_TTL);
        
        return new LengthAwarePaginator($posts, $total, $perPage, $page);
    }

    protected function invalidateCache(): void
    {
        // Удаляем все ключи кеша
        $this->cache->forget(self::CACHE_PREFIX . 'total');
        $this->cache->forget(self::CACHE_PREFIX . 'page:1:perPage:10');
        $this->cache->forget(self::CACHE_PREFIX . 'page:1:perPage:10:filter:title=Test');
        $this->cache->forget(self::CACHE_PREFIX . 'page:1:perPage:10:filter:title=New');
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        $this->invalidateCache();
        return $model;
    }
} 