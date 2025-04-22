<?php

namespace App\Providers;

use App\Contracts\CacheInterface;
use App\Services\Cache\RedisCacheService;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CacheInterface::class, RedisCacheService::class);
    }

    public function boot(): void
    {
        //
    }
} 