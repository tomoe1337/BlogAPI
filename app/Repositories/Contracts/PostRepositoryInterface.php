<?php

namespace App\Repositories\Contracts;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface extends RepositoryInterface
{
    /**
     * Создать пост с тегами и категорией
     */
    public function createWithRelations(array $data): Post;

    /**
     * Обновить пост с тегами и категорией
     */
    public function updateWithRelations(Post $post, array $data): Post;

    /**
     * Получить посты с отношениями
     */
    public function getWithRelations(): Collection;
} 