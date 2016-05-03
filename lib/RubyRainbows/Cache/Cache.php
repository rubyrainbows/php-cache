<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace RubyRainbows\Cache;

use RubyRainbows\Cache\Providers\Base\BaseClient;
use RubyRainbows\Cache\Providers\Base\Objects\BaseObject;
use RubyRainbows\Cache\Providers\Base\Objects\BaseTree;
use RubyRainbows\Cache\Providers\Redis\RedisClient;

/**
 * Class Cache
 *
 * Interacts with cache providers to create various cached objects.
 *
 * @package RubyRainbows\Cache
 *
 */
class Cache
{
    /**
     * @var BaseClient
     */
    private $client = null;

    /**
     * Cache constructor.
     *
     * Generates a client to talk to the cache.
     *
     * Usage:
     *      $cache = new Cache(CacheProviders::Redis, 'tcp://127.0.0.1:6379?database=0', []);
     *      $cache = new Cache(CacheProviders::Redis, ['tcp://127.0.0.1:6379?database=0'], []);
     *      $cache = new Cache(CacheProviders::Redis, ['tcp://127.0.0.1:6379?database=0&alias=master',
     *                                                 'tcp://127.0.0.2:6379?database=0&alias=slave-01], []);
     *
     * @param int             $type
     * @param string|string[] $connectionStrings
     * @param array           $cacheOptions
     */
    public function __construct ( $type = CacheProviders::REDIS, $connectionStrings = null, $cacheOptions = [] )
    {
        switch ( $type )
        {
            case CacheProviders::REDIS:
                $this->client = new RedisClient($connectionStrings, $cacheOptions);
                break;
            default;
                $this->client = new RedisClient($connectionStrings, $cacheOptions);
                break;
        }
    }

    /**
     * Generates a hash object which you can use to interact with the cache.
     *
     * Usage:
     *      $cache = new Cache();
     *      $object = $cache->createObject('user:tmuntan1', ['name' => 'Thomas Muntaner', 'country' => 'Germany']);
     *
     *      This will create a new hash under the name 'user:tmuntan1' with the following data:
     *          name: Thomas Muntaner
     *          country: Germany
     *
     * @param string $key
     * @param array  $data
     * @param int    $expire
     *
     * @return BaseObject
     */
    public function createObject ( $key, $data = [], $expire = 0 )
    {
        return $this->client->createObject($key, $data, $expire);
    }

    /**
     * Creates a tree object in the cache.
     *
     * Usage:
     *      $cache = new Cache();
     *      $tree = $cache->createTree('my_tree');
     *
     * See BaseTree for the usage of the tree object.
     *
     * @param string $key
     *
     * @return BaseTree
     */
    public function createTree ( $key )
    {
        return $this->client->createTree($key);
    }

    /**
     * Returns the generated client so that you may work with it.
     *
     * Usage:
     *      $cache = new Cache();
     *      $client = $cache->getClient();
     *
     * @return BaseClient
     */
    public function getClient ()
    {
        return $this->client;
    }
}
