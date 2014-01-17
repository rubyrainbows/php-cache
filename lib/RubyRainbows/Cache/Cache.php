<?php

/**
 * Cache.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 *
 */

namespace RubyRainbows\Cache;

use RubyRainbows\Cache\Providers\Redis\Client as RedisClient;
use RubyRainbows\Cache\Providers\Redis\Objects\Object as RedisObject;
use RubyRainbows\Cache\Providers\Redis\Objects\Tree as RedisTree;

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
    private static $currentCache = CacheProviders::REDIS;

    /**
     * Sets up the cache
     *
     * @param $type
     * @param array $cache_config
     */
    public static function setup($type, $cache_config=[])
    {
        switch ($type):

            case CacheProviders::REDIS:
               RedisClient::setup($cache_config);
               self::$currentCache = CacheProviders::REDIS;
               break;

            default:
                break;

        endswitch;
    }

    /**
     * Returns the current cache type
     *
     * @return int
     */
    public static function currentCache()
    {
        return self::$currentCache;
    }

    /**
     * Creates a cached object
     *
     * @param   $key
     * @param   $type
     *
     * @return null|RedisObject
     */
    public static function object($key, $type)
    {
        $object = null;

        switch (self::$currentCache):
            case CacheProviders::REDIS:
                switch ($type):
                    case ObjectType::OBJECT:
                        return new RedisObject($key);
                    case ObjectType::TREE:
                        return new RedisTree($key);
                endswitch;
                break;
        endswitch;

        return $object;
    }

}