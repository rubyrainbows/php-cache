<?php

/**
 * Object.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Redis\Objects;

use RubyRainbows\Cache\Providers\Redis\RedisClient;
use RubyRainbows\Cache\Providers\Base;

/**
 * Class Object
 *
 * An object that interacts whose data is stored in redis
 *
 * @package RubyRainbows\Cache\Providers\Redis\Objects
 *
 */
class RedisObject implements Base\Objects\BaseObject
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $data;

    /**
     * @var RedisClient
     */
    private $client;

    /**
     * Constructs a redis object
     *
     * @param       $key
     * @param array $data
     * @param int   $expire
     */
    public function __construct ( $key, array $data = [], $expire = 0 )
    {
        $this->client = new RedisClient();

        $this->key = $key;
        $this->data = $this->getAll();

        if ( $expire != 0 )
            $this->expire($expire);

        $this->fill($data);
    }


    /**
     * Expires the object after a set time
     *
     * @param int $time The time to expire
     */
    public function expire ( $time )
    {
        $this->client->expire($this->key, $time);
    }

    /**
     * Sets a field for the object
     *
     * @param $field
     * @param $value
     *
     * @return mixed|void
     */
    public function set ( $field, $value )
    {
        $this->client->setHashValue($this->key, $field, $value);

        $this->data = $this->getAll();
    }

    /**
     * Fills in fields for the object
     *
     * @param array $data
     */
    public function fill ( array $data )
    {
        foreach ( $data as $key => $value )
            $this->set($key, $value);

        $this->data = $this->getAll();
    }

    /**
     * Gets a variable from the redis cache for the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function get ( $field )
    {
        return $this->client->getHashValue($this->key, $field);
    }

    /**
     * Gets all the data from the redis store
     *
     * @return mixed
     */
    public function getAll ()
    {
        return $this->client->getHashFull($this->key);
    }

    /**
     * Deletes all the data from the redis store
     *
     * @param      $key
     * @param bool $refreshData
     *
     * @return mixed|void
     */
    public function delete ( $key, $refreshData = true )
    {
        $this->client->deleteHashValue($this->key, $key);

        if ( $refreshData )
            $this->data = $this->getAll();
    }

    /**
     * Deletes all the data from the redis store for the object
     */
    public function deleteAll ()
    {
        $data = $this->client->getHashFull($this->key);

        foreach ( $data as $key => $value )
            $this->delete($key, false);

        $this->data = [];
    }
}
