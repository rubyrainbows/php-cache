<?php

use RubyRainbows\Cache\Cache;

class CacheTest extends TestCase
{
    public function testCacheSetup()
    {
        Cache::setup(Cache::REDIS_CACHE);

        $this->assertEquals(Cache::REDIS_CACHE, Cache::currentCache());
    }

    public function testObjectFunction()
    {
        $object = Cache::object('key', ['foo', 'bar']);
        $this->assertNotNull($object);
    }

    public function testTreeFunction()
    {
        $tree = Cache::tree('key');
        $this->assertNotNull($tree);
    }
}