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
        self::$config['host']       = (array_key_exists('host', $config))       ? $config['host']       : '127.0.0.1';
        self::$config['database']   = (array_key_exists('database', $config))   ? $config['database']   : '0';

        return self::connect();
    }

    /**
     * Gets the value from the redis store
     *
     * @param $key
     *
     */
    public static function get($key)
    {
        return self::redisFunction( 'get', $key );
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
        $return = self::redisFunction( 'hget', $key, $field );

        if ( !$return )
            return array();

        return $return;
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
        return self::redisFunction( 'hset', $key, $field, $value );
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
        return self::redisFunction( 'hgetall', $key );
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
        return self::redisFunction( 'hdel', $key, $field );
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
        return self::redisFunction( 'set', $key, $value );
    }

    /**
     * Clears the redis database
     *
     */
    public static function clear()
    {
        return self::redisFunction( 'flushdb' );
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

            try
            {
                self::$redis->connect();
            }
            catch ( \Predis\Connection\ConnectionException $e )
            {
                self::$redis = null;
                return false;
            }
        }

        return self::$redis;
    }

    public static function redisFunction()
    {
        $redis      = self::connect();
        $args       = func_get_args();
        $function   = array_shift( $args );

        if ( $redis )
        {
            try
            {
                return call_user_func_array( array($redis, $function), $args );
            }
            catch ( \Predis\ServerException $e )
            {
                throw new Exceptions\CommandException( "Command '{$function}' with arguments " . join( $args, ", " ) . " failed!" );
            }
        }
        else
        {
            throw new Exceptions\ConnectionException( "Could not connect to redis!" );
        }

        return false;
    }

}