<?php

namespace RubyRainbows\Cache\Providers\Base;

use RubyRainbows\Cache\Providers\Base\Objects\BaseObject;
use RubyRainbows\Cache\Providers\Base\Objects\BaseTree;

interface BaseClient
{
    /**
     * Configures the Redis Client
     *
     * @param array $config
     * @param array $options
     *
     * @return mixed
     */
    public function setup ( $config = null, $options = [] );

    /**
     * Expires a key in redis after a set time
     *
     * @param string  $key  The key in redis
     * @param integer $time The time to expire
     *
     * @return mixed
     */
    public function expire ( $key, $time );

    /**
     * Gets the value from the redis store
     *
     * @param $key
     *
     * @return mixed
     */
    public function get ( $key );

    /**
     * Gets a hash value from the redis store
     *
     * @param $key
     * @param $field
     *
     * @return mixed
     */
    public function getHashValue ( $key, $field );

    /**
     * Sets a hash value in the redis store
     *
     * @param $key
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function setHashValue ( $key, $field, $value );

    /**
     * Gets all the hash values from the redis store
     *
     * @param $key
     *
     * @return mixed
     */
    public function getHashFull ( $key );

    /**
     * Deletes a hash value from the redis store
     *
     * @param $key
     * @param $field
     *
     * @return mixed
     */
    public function deleteHashValue ( $key, $field );

    /**
     * Sets a value in the redis store
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function set ( $key, $value );

    /**
     * Clears the redis database
     */
    public function clear ();

    /**
     *  Gets all keys from the redis store matching pattern
     *
     * @param $pattern
     *
     * @return mixed
     */
    public function keys ( $pattern );

    /**
     * Returns a new object
     *
     * @param $key
     * @param $data
     * @param $expire
     *
     * @return BaseObject
     */
    public function createObject ( $key, $data, $expire );

    /**
     * Returns a new tree object
     *
     * @param $key
     *
     * @return BaseTree
     */
    public function createTree ( $key );
}