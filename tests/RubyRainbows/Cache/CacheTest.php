<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use RubyRainbows\Cache\Cache;
use RubyRainbows\Cache\CacheProviders;
use RubyRainbows\Cache\Providers\Redis\Objects\RedisObject;
use RubyRainbows\Cache\Providers\Redis\Objects\RedisTree;
use RubyRainbows\Cache\Providers\Redis\RedisClient;

class CacheTest extends TestCase
{
    /**
     * @var Cache
     */
    private $cache;

    public function setUp ()
    {
        parent::setUp();

        $this->cache = new Cache(CacheProviders::REDIS);
    }

    public function testGetWithDifferentConstructors ()
    {
        // string
        $this->cache = new Cache(CacheProviders::REDIS, 'tcp://127.0.0.1:6379?database=0');
        $this->assertNull($this->cache->getClient()->get('constructor_string'));
        $this->cache->getClient()->set('constructor_string', 'foo');
        $this->assertEquals('foo', $this->cache->getClient()->get('constructor_string'));

        // array
        $this->cache = new Cache(CacheProviders::REDIS, ['tcp://127.0.0.1:6379?database=0']);
        $this->assertNull($this->cache->getClient()->get('constructor_array'));
        $this->cache->getClient()->set('constructor_array', 'foo');
        $this->assertEquals('foo', $this->cache->getClient()->get('constructor_array'));
    }

    public function testGetObject ()
    {
        $object = $this->cache->createObject('foo');
        $this->assertNotNull($object);
        $this->assertEquals(get_class(new RedisObject($this->client, 'bar')), get_class($object));
    }

    public function testGetTree ()
    {
        $tree = $this->cache->createTree('foo');
        $this->assertNotNull($tree);
        $this->assertEquals(get_class(new RedisTree($this->client, 'bar')), get_class($tree));
    }

    public function testGetClient ()
    {
        $client = $this->cache->getClient();
        $this->assertNotNull($client);
        $this->assertEquals(get_class(new RedisClient()), get_class($client));
    }
}
