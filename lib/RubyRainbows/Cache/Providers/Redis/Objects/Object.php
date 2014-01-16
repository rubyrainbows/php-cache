<?php

namespace RubyRainbows\Cache\Providers\Redis\Objects;

use RubyRainbows\Cache\Providers\Redis\Client as Client;
use RubyRainbows\Cache\Objects;

class Object implements Objects\CachedObject
{
    private static $key     = "";
    private static $data    = [];

    /**
     * Constructs a redis object
     *
     * @param $key
     * @param array $data
     */
    public function __construct($key, $data=[])
    {
        self::$key = $key;

        foreach ($data as $data_key => $value)
        {
            Client::hset(self::$key, $data_key, $value);
        }

        self::$data = $this->getAll();
    }

    /**
     * Sets a value in the redis store for the object
     *
     * @param $field
     * @param $value
     */
    public function __set($field, $value)
    {
        Client::hset(self::$key, $field, $value);

        self::$data = $this->getAll();
    }

    /**
     * Gets the data from the redis store for the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function __get($field)
    {
        return Client::hget(self::$key, $field);
    }

    /**
     * Gets all the data from the redis store
     *
     * @return mixed
     */
    public function getAll()
    {
        return Client::hgetall(self::$key);
    }

    /**
     * Deletes all the data from the redis store
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