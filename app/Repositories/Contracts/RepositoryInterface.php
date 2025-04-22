<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * Получить все записи
     */
    public function all(): Collection;

    /**
     * Найти запись по ID
     */
    public function find(int $id): ?Model;

    /**
     * Создать новую запись
     */
    public function create(array $data): Model;

    /**
     * Обновить запись
     */
    public function update(Model $model, array $data): Model;

    /**
     * Удалить запись
     */
    public function delete(Model $model): bool;
} 