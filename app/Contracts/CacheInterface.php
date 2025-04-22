<?php

namespace App\Contracts;

interface CacheInterface
{
    public function get(string $key): mixed;
    public function put(string $key, mixed $value, int $ttl = 3600): bool;
    public function forget(string $key): bool;
    public function has(string $key): bool;
} 