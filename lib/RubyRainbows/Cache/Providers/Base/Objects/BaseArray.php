<?php

/**
 * BaseArray.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 *
 */

namespace RubyRainbows\Cache\Providers\Base\Objects;

/**
 * Interface CachedArray
 *
 * An array that interacts directly with the cache's store
 *
 * @package RubyRainbows\Cache\Providers\Base\Objects
 *
 */
interface CachedArray {

    /**
     * Constructs the array with the cache key
     *
     * @param $key
     */
    public function __construct($key);

    /**
     * Adds an object to the array
     *
     * @param $object
     *
     * @return mixed
     */
    public function add($object);

    /**
     * Removes an element from the array
     * @param $object
     *
     * @return mixed
     */
    public function remove($object);

    /**
     * Returns the objects of the array
     *
     * @return mixed
     */
    public function objects();

} 