<?php

namespace RubyRainbows\Cache\Providers\Redis;

use Predis\Client;
use RubyRainbows\Cache\Providers\Base\BaseClient;
use RubyRainbows\Cache\Providers\Base\Objects\BaseObject;
use RubyRainbows\Cache\Providers\Base\Objects\BaseTree;
use RubyRainbows\Cache\Providers\Redis\Exceptions\CommandException;
use RubyRainbows\Cache\Providers\Redis\Exceptions\ConnectionException;
use RubyRainbows\Cache\Providers\Redis\Objects\RedisObject;
use RubyRainbows\Cache\Providers\Redis\Objects\RedisTree;

/**
 * Class RedisClient
 *
 * This class implements the BaseClient cache interface for a redis store.
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
     * @var string|string[]
     */
    private $connectionStrings;

    /**
     * @var array
     */
    private $options = [];

    /**
     * Constructs the redis client
     *
     * @param string|string[]|null $connectionStrings
     * @param array $options
     */
    public function __construct ( $connectionStrings = null, $options = [] )
    {
        if ( $connectionStrings == null )
        {
            $this->connectionStrings = 'tcp://127.0.0.1:6379?database=0';
        }
        else
        {
            $this->connectionStrings = $connectionStrings;
        }

        $this->options = $options;
    }

    /**
     * Returns the connection string for redis
     *
     * @return string
     */
    public function getConnectionStrings ()
    {
        return $this->connectionStrings;
    }

    /**
     * Expires a key in redis after a set time
     *
     * @param string  $key  The key in redis
     * @param integer $time The time to expire
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function expire ( $key, $time )
    {
        $result = $this->redisFunction('expire', $key, $time);

        return ($result == 1);
    }

    /**
     * Gets the value from the redis store
     *
     * @param string $key
     *
     * @return string
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function get ( $key )
    {
        return $this->redisFunction('get', $key);
    }

    /**
     * Gets a hash value from the redis store
     *
     * @param string $key
     * @param string $field
     *
     * @return string
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function getHashValue ( $key, $field )
    {
        $return = $this->redisFunction('hget', $key, $field);

        if ( !$return )
        {
            return [];
        }

        return $return;
    }

    /**
     * Sets a hash value in the redis store
     *
     * @param string $key
     * @param string $field
     * @param string $value
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function setHashValue ( $key, $field, $value )
    {
        return $this->redisFunction('hset', $key, $field, $value);
    }

    /**
     * Gets all the hash values from the redis store
     *
     * @param string $key
     *
     * @return array
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function getHashFull ( $key )
    {
        return $this->redisFunction('hgetall', $key);
    }

    /**
     * Deletes a hash value from the redis store
     *
     * @param string $key
     * @param string $field
     *
     * @return mixed
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function deleteHashValue ( $key, $field )
    {
        return $this->redisFunction('hdel', $key, $field);
    }

    /**
     * Deletes a value from the redis store
     *
     * @param $key
     *
     * @return mixed
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function delete ( $key )
    {
        return $this->redisFunction('del', $key);
    }

    /**
     * Sets a value in the redis store
     *
     * @param string $key
     * @param string $value
     * @param int    $expire
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function set ( $key, $value, $expire = 0 )
    {
        $result = $this->redisFunction('set', $key, $value);

        if ( $result != 'OK' )
        {
            return false;
        }

        if ( $expire != 0 )
        {
            $result = $this->expire($key, $expire);
        }

        return $result;
    }

    /**
     * Clears the redis database
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function clear ()
    {
        return $this->redisFunction('flushdb');
    }


    /**
     * Gets all keys from the redis store matching pattern
     *
     * @param string $pattern
     *
     * @return array
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function getKeys ( $pattern )
    {
        return $this->redisFunction('keys', $pattern);
    }

    /**
     * Gets all the keys from the redis store matching the pattern.
     *
     * @param $pattern string
     *
     * @return mixed
     * @throws CommandException
     * @throws ConnectionException
     */
    public function getHashKeys ( $pattern )
    {
        return $this->redisFunction('hkeys', $pattern);
    }

    /**
     * Returns a new object
     *
     * @param string  $key
     * @param array   $data
     * @param integer $expire
     *
     * @return BaseObject
     */
    public function createObject ( $key, $data, $expire )
    {
        return new RedisObject($this, $key, $data, $expire);
    }

    /**
     * Returns a new tree object
     *
     * @param string $key
     * @param integer $expire
     *
     * @return BaseTree
     */
    public function createTree ( $key, $expire = 0)
    {
        return new RedisTree($this, $key, $expire);
    }

    /**
     * Increments an integer key
     *
     * @param $key
     *
     * @return integer
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function increment ( $key )
    {
        return $this->redisFunction('INCR', $key);
    }

    /**
     * Runs a redis function
     *
     * @return mixed
     *
     * @throws CommandException
     * @throws ConnectionException
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
                throw new CommandException($function, $args);
            }
        }

        throw new ConnectionException();
    }

    /**
     * Connects to the redis client
     *
     * @return Client
     */
    private function connect ()
    {
        if ( $this->redis == null )
        {
            $this->redis = new Client($this->connectionStrings, $this->options);

            try
            {
                $this->redis->connect();
            }
            catch ( \Exception $e )
            {
                $this->redis = null;

                return null;
            }
        }

        return $this->redis;
    }
}
