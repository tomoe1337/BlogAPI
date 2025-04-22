<?php

namespace App\Services\Cache;

use App\Contracts\CacheInterface;
use Illuminate\Support\Facades\Redis;

class RedisCacheService implements CacheInterface
{
    public function get(string $key): mixed
    {
        $value = Redis::get($key);
        return $value ? json_decode($value, true) : null;
    }

    public function put(string $key, mixed $value, int $ttl = 3600): bool
    {
        $result = Redis::setex($key, $ttl, json_encode($value));
        return $result === 'OK';
    }

    public function forget(string $key): bool
    {
        return Redis::del($key) > 0;
    }

    public function has(string $key): bool
    {
        return Redis::exists($key) > 0;
    }
} 