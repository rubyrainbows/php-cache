<?php

namespace RubyRainbows\Cache;

use RubyRainbows\Cache\Providers\Redis\Client as RedisClient;
class Cache
{
    const REDIS_CACHE = 1;

    private static $currentCache = null;

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

    public static function currentCache()
    {
        return self::$currentCache;
    }
}