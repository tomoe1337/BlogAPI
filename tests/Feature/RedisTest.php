<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RedisTest extends TestCase
{
    #[Test]
    public function test_redis_connection()
    {
        try {
            Redis::set('test', 'test');
            $value = Redis::get('test');
            $this->assertEquals('test', $value);
            Redis::del('test');
        } catch (\Exception $e) {
            $this->fail('Redis connection failed: ' . $e->getMessage());
        }
    }
} 