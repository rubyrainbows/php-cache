<?php

use RubyRainbows\Cache\Cache;

class CacheTest extends TestCase
{
    public function testCacheSetup()
    {
        Cache::setup(Cache::REDIS_CACHE);

        $this->assertEquals(Cache::REDIS_CACHE, Cache::currentCache());
    }
}