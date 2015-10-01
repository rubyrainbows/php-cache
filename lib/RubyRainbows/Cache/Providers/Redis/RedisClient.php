<?php

/**
 * Client.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Redis;

use RubyRainbows\Cache\Providers\Base\BaseClient;
use RubyRainbows\Cache\Providers\Base\Objects\BaseObject;
use RubyRainbows\Cache\Providers\Base\Objects\BaseTree;
use RubyRainbows\Cache\Providers\Redis\Objects\RedisObject;
use RubyRainbows\Cache\Providers\Redis\Objects\RedisTree;
use Predis\Client;

/**
 * Class RedisClient
 *
 * A client for redis
 *
 * @package RubyRainbows\Cache\Providers\Redis
 *
 */
class RedisClient implements BaseClient
{
    /**
     * @var Client
     */
    private $redis = null;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * Configures the Redis Client
     *
     * @param array $config
     * @param array $options
     *
     * @return mixed
     */
    public function setup ( $config = null, $options = [] )
    {
        $this->config = ($config) ? $config : 'tcp://127.0.0.1:6379?database=0';
        $this->options = $options;

        return $this->connect();
    }

    /**
     * Expires a key in redis after a set time
     *
     * @param string  $key  The key in redis
     * @param integer $time The time to expire
     *
     * @return mixed
     */
    public function expire ( $key, $time )
    {
        return $this->redisFunction('expire', $key, $time);
    }

    /**
     * Gets the value from the redis store
     *
     * @param $key
     *
     * @return mixed
     */
    public function get ( $key )
    {
        return $this->redisFunction('get', $key);
    }

    /**
     * Gets a hash value from the redis store
     *
     * @param $key
     * @param $field
     *
     * @return mixed
     */
    public function getHashValue ( $key, $field )
    {
        $return = $this->redisFunction('hget', $key, $field);

        if ( !$return )
            return [];

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
     */
    public function setHashValue ( $key, $field, $value )
    {
        return $this->redisFunction('hset', $key, $field, $value);
    }

    /**
     * Gets all the hash values from the redis store
     *
     * @param $key
     *
     * @return mixed
     */
    public function getHashFull ( $key )
    {
        return $this->redisFunction('hgetall', $key);
    }

    /**
     * Deletes a hash value from the redis store
     *
     * @param $key
     * @param $field
     *
     * @return mixed
     */
    public function deleteHashValue ( $key, $field )
    {
        return $this->redisFunction('hdel', $key, $field);
    }

    /**
     * Sets a value in the redis store
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function set ( $key, $value )
    {
        return $this->redisFunction('set', $key, $value);
    }

    /**
     * Clears the redis database
     */
    public function clear ()
    {
        return $this->redisFunction('flushdb');
    }


    /**
     *  Gets all keys from the redis store matching pattern
     *
     * @param $pattern
     *
     * @return mixed
     */
    public function keys ( $pattern )
    {
        return $this->redisFunction('keys', $pattern);
    }

    /**
     * Runs a redis function
     *
     * @return mixed
     *
     * @throws Exceptions\CommandException
     * @throws Exceptions\ConnectionException
     */
    private function redisFunction ()
    {
        $redis = $this->connect();
        $args = func_get_args();
        $function = array_shift($args);

        if ( $redis )
        {
            try
            {
                return call_user_func_array([$redis, $function], $args);
            }
            catch ( \Exception $e )
            {
                throw new Exceptions\CommandException($function, $args);
            }
        }

        throw new Exceptions\ConnectionException();
    }

    /**
     * Connects to the redis client
     *
     * @return null|RedisClient
     */
    private function connect ()
    {
        if ( $this->config == null )
            $this->setup();

        if ( $this->redis == null )
        {
            $this->redis = new Client($this->config, $this->options);

            try
            {
                $this->redis->connect();
            }
            catch ( \Exception $e )
            {
                $this->redis = null;

                return false;
            }
        }

        return $this->redis;
    }

    /**
     * Returns a new object
     *
     * @param $key
     * @param $data
     * @param $expire
     *
     * @return BaseObject
     */
    public function createObject ( $key, $data, $expire )
    {
        return new RedisObject($key, $data, $expire);
    }

    /**
     * Returns a new tree object
     *
     * @param $key
     *
     * @return BaseTree
     */
    public function createTree ( $key )
    {
        return new RedisTree($key);
    }
}
