<?php

namespace RubyRainbows\Cache\Providers\Redis;

class Object
{
    private static $key = "";

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
    }

    /**
     * Sets a value in the redis store for the object
     *
     * @param $field
     * @param $value
     */
    public function set($field, $value)
    {
        Client::hset(self::$key, $field, $value);
    }

    /**
     * Gets the data from the redis store for the object
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
     */
    public function getAll()
    {
        return Client::hgetall(self::$key);
    }

    /**
     * Deletes all the data from the redis store
     */
    public function delete($key)
    {
        Client::hdel(self::$key, $key);
    }

    public function deleteAll()
    {
        $data = Client::hgetall(self::$key);

        foreach ($data as $key => $value)
        {
            $this->delete($key);
        }
    }
}