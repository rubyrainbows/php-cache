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

/**
 * Interface CachedObject
 *
 * An object that interacts directly with the cache's store.
 *
 * @package RubyRainbows\Cache\Objects
 */
interface CachedObject
{
    /**
     * Creates a cached object
     *
     * @param $key
     *
     * @param array $data
     */
    public function __construct($key, array $data=[]);

    /**
     * Sets a field's value for the object
     *
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function __set($field, $value);

    /**
     * Gets a field's value for the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function __get($field);

    /**
     * Gets all the values for the object
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Deletes a field from the object
     *
     * @param $key
     * @param bool $refreshData
     *
     * @return mixed
     */
    public function delete($key, $refreshData=true);

    /**
     * Delete's all the fields from the object
     *
     * @return mixed
     */
    public function deleteAll();
}