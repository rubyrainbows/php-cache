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
    const REDIS_CACHE = 1;

    private static $currentCache = Cache::REDIS_CACHE;

    /**
     * Sets up the cache
     *
     * @param $type
     * @param array $cache_config
     */
    public static function setup($type, $cache_config=[])
    {
        switch ($type):

            case self::REDIS_CACHE:
               RedisClient::setup($cache_config);
               self::$currentCache = self::REDIS_CACHE;
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
     * @param $key
     * @param array $data
     *
     * @return null|RedisObject
     */
    public static function object($key, array $data=[])
    {
        $object = null;

        switch (self::$currentCache):
            case self::REDIS_CACHE:
                $object = new RedisObject($key, $data);
                break;
        endswitch;

        return $object;
    }

    /**
     * Creates a cached tree
     *
     * @param $key
     *
     * @return null|RedisTree
     */
    public static function tree($key)
    {
        $tree = null;

        switch (self::$currentCache):
            case self::REDIS_CACHE:
                $tree = new RedisTree($key);
                break;
        endswitch;

        return $tree;
    }
}