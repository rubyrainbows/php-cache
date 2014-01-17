<?php

use RubyRainbows\Cache\Cache;

class CacheTest extends TestCase
{
    public function testCacheSetup()
    {
        Cache::setup(\RubyRainbows\Cache\CacheProviders::REDIS);

        $this->assertEquals(\RubyRainbows\Cache\CacheProviders::REDIS, Cache::currentCache());
    }

}