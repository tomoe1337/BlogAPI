<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Repository implements PostRepositoryInterface, RepositoryInterface
{
    public function all(): Collection
    {
        return Post::all();
    }

    public function find(int $id): ?Model
    {
        return Post::find($id);
    }

    public function create(array $data): Model
    {
        return Post::create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model;
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function createWithRelations(array $data): Post
    {
        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $data['image'] ?? null,
            'category_id' => $data['category_id'] ?? null
        ]);

        if (isset($data['tags'])) {
            $post->tags()->attach($data['tags']);
        }

        return $post->load('tags', 'category');
    }

    public function updateWithRelations(Post $post, array $data): Post
    {
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $data['image'] ?? null,
            'category_id' => $data['category_id'] ?? $post->category_id
        ]);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return $post->load('tags', 'category');
    }

    public function getWithRelations(): Collection
    {
        return Post::with('tags', 'category')->get();
    }
} 