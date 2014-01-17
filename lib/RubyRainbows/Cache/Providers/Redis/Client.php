<?php

/**
 * Client.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 *
 */

namespace RubyRainbows\Cache\Providers\Redis;

use Predis\Client as RedisClient;

/**
 * Class Client
 *
 * Redis client
 *
 * @package RubyRainbows\Cache\Providers\Redis
 *
 */
class Client
{
    private static $redis   = null;
    private static $config  = [];

    /**
     * Configures the Redis Client
     *
     * @param array $config
     *
     */
    public static function setup($config=[])
    {
        self::$config['scheme']     = (array_key_exists('scheme', $config))     ? $config['scheme']     : 'tcp';
        self::$config['host']       = (array_key_exists('host', $config))       ? $config['host']       : 'localhost';
        self::$config['database']   = (array_key_exists('database', $config))   ? $config['database']   : '0';

        self::connect();
    }

    /**
     * Gets the value from the redis store
     *
     * @param $key
     *
     */
    public static function get($key)
    {
        $redis = self::connect();

        return $redis->get($key);
    }

    /**
     * Gets a hash value from the redis store
     *
     * @param $key
     * @param $field
     *
     * @return mixed
     *
     */
    public static function hget($key, $field)
    {
        $redis = self::connect();

        return $redis->hget($key, $field);
    }

    /**
     * Sets a hash value in the redis store
     *
     * @param $key
     * @param $field
     * @param $value
     *
     * @return mixed
     *
     */
    public static function hset($key, $field, $value)
    {
        $redis = self::connect();

        return $redis->hset($key, $field, $value);
    }

    /**
     * Gets all the hash values from the redis store
     *
     * @param $key
     *
     * @return mixed
     *
     */
    public static function hgetall($key)
    {
        $redis = self::connect();

        return $redis->hgetall($key);
    }

    /**
     * Deletes a hash value from the redis store
     *
     * @param $key
     * @param $field
     *
     * @return mixed
     *
     */
    public static function hdel($key, $field)
    {
        $redis = self::connect();

        return $redis->hdel($key, $field);
    }

    /**
     * Sets a value in the redis store
     *
     * @param $key
     * @param $value
     *
     */
    public static function set($key, $value)
    {
        $redis = self::connect();

        $redis->set($key, $value);
    }

    /**
     * Clears the redis database
     *
     */
    public static function clear()
    {
        $redis = self::connect();

        $redis->flushall();
    }

    /**
     * Connects to the redis client
     *
     * @return null|RedisClient
     *
     */
    private static function connect()
    {
        if ( self::$config == null )
        {
            self::setup();
        }

        if ( self::$redis == null )
        {
            self::$redis = new RedisClient( self::$config );
        }

        return self::$redis;
    }

}