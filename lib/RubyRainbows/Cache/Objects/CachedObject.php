<?php

/**
 * CachedObject.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 * 
 */

namespace RubyRainbows\Cache\Objects;

use RubyRainbows\Cache\Cache;
use RubyRainbows\Cache\Providers\Base\Objects\BaseObject as BaseObject;
use RubyRainbows\Cache\ObjectType;

/**
 * Class CachedObject
 *
 * A cached object that interacts with a set cache store to save data.
 *
 * @package RubyRainbows\Cache\Objects
 *
 */
class CachedObject
{
    private     $base       = null;
    protected   $namespace  = "";
    protected   $key        = "";

    /**
     * Constructs the Cached Object
     *
     * @param       $key
     * @param array $opts
     */
    public function __construct ($key, array $opts=[])
    {
        $this->namespace    = (array_key_exists('namespace', $opts))    ? $opts['namespace']            : $this->namespace;
        $this->key          = ($this->namespace != "")                  ? $this->namespace . ':' . $key : $key;
        $this->base         = (array_key_exists('base', $opts))         ? $opts['base']                 : Cache::object($this->key, ObjectType::OBJECT);
    }

    /**
     * Gets a field from the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function get($field)
    {
        return $this->base->get($field);
    }

    /**
     * Gets a field from the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function __get ($field)
    {
        return $this->get($field);
    }

    /**
     * Set a field for the object
     *
     * @param $field
     * @param $value
     */
    public function set ($field, $value)
    {
        $this->base->set($field, $value);
    }

    /**
     * Sets a field for the object
     *
     * @param $field
     * @param $value
     */
    public function __set ($field, $value)
    {
        $this->set($field, $value);
    }

    /**
     * Fills a object with data
     *
     * @param array $data
     */
    public function fill (array $data)
    {
        $this->base->fill($data);
    }

    /**
     * Gets all the fields for the object
     *
     * @return mixed
     */
    public function getAll ()
    {
        return $this->base->getAll();
    }

    /**
     * Deletes a single field from the object
     *
     * @param $key
     */
    public function delete($key)
    {
        $this->base->delete($key, true);
    }

    /**
     * Deletes all the fields from the object
     *
     */
    public function deleteAll()
    {
        $this->base->deleteAll();
    }
} 