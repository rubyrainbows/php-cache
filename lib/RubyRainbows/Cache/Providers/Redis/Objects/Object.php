<?php

/**
 * Object.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 *
 */

namespace RubyRainbows\Cache\Providers\Redis\Objects;

use RubyRainbows\Cache\Providers\Redis\Client as Client;
use RubyRainbows\Cache\Providers\Base;

/**
 * Class Object
 *
 * An object that interacts whose data is stored in redis
 *
 * @package RubyRainbows\Cache\Providers\Redis\Objects
 *
 */
class Object implements Base\Objects\BaseObject
{
    private static $key     = "";
    private static $data    = [];

    /**
     * Constructs a redis object
     *
     * @param $key
     * @param array $data
     *
     */
    public function __construct($key, array $data=[])
    {
        self::$key  = $key;
        self::$data = $this->getAll();

        $this->fill($data);
    }

    /**
     * Sets a value in the redis store for the object
     *
     * @param $field
     * @param $value
     *
     * @return mixed|void
     *
     */
    public function __set($field, $value)
    {
        $this->set($field, $value);
    }

    /**
     * Sets a field for the object
     *
     * @param $field
     * @param $value
     *
     * @return mixed|void
     */
    public function set($field, $value)
    {
        Client::hset(self::$key, $field, $value);

        self::$data = $this->getAll();
    }

    /**
     * Fills in fields for the object
     *
     * @param array $data
     */
    public function fill (array $data)
    {
        foreach ($data as $key => $value)
        {
            $this->set($key, $value);
        }

        self::$data = $this->getAll();
    }
    /**
     * Gets the data from the redis store for the object
     *
     * @param $field
     *
     * @return mixed
     *
     */
    public function __get($field)
    {
        return $this->get($field);
    }

    /**
     * Gets a variable from the redis cache for the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function get($field)
    {
        return Client::hget(self::$key, $field);
    }

    /**
     * Gets all the data from the redis store
     *
     * @return mixed
     *
     */
    public function getAll()
    {
        return Client::hgetall(self::$key);
    }

    /**
     * Deletes all the data from the redis store
     *
     */
    public function delete($key, $refreshData=true)
    {
        Client::hdel(self::$key, $key);

        if ( $refreshData )
        {
            self::$data = $this->getAll();
        }
    }

    /**
     * Deletes all the data from the redis store for the object
     *
     */
    public function deleteAll()
    {
        $data = Client::hgetall(self::$key);

        foreach ( $data as $key => $value )
        {
            $this->delete($key, false);
        }

        self::$data = [];
    }
}