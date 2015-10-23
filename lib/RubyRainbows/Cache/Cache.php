<?php

/**
 * Cache.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
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

    public function __construct ( $type = CacheProviders::REDIS, $cacheConfig = [], $cacheOptions = [] )
    {
        switch ( $type )
        {
            case CacheProviders::REDIS:
                $this->client = new RedisClient($cacheConfig, $cacheOptions);
                break;
            default;
                break;
        }
    }

    /**
     * Creates a cached object
     *
     * @param       $key
     * @param array $data
     * @param int   $expire
     *
     * @return BaseObject
     */
    public function createObject ( $key, $data = [], $expire = 0 )
    {
        return $this->client->createObject($key, $data, $expire);
    }

    /**
     * Creates a cached tree
     *
     * @param $key
     *
     * @return BaseTree
     */
    public function createTree ( $key )
    {
        return $this->client->createTree($key);
    }

    /**
     * Returns a client
     *
     * @return BaseClient
     */
    public function getClient ()
    {
        return $this->client;
    }
}
