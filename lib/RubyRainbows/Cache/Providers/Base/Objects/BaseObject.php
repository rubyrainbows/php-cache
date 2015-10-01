<?php

/**
 * BaseObject.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Base\Objects;

/**
 * Interface BaseObject
 *
 * An object that interacts directly with the cache's store.
 *
 * @package RubyRainbows\Cache\Objects
 *
 */
interface BaseObject
{
    /**
     * Expires the object after a set time
     *
     * @param integer $expire The time to expire
     */
    public function expire ( $expire );

    /**
     * Sets a field's value for the object
     *
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function set ( $field, $value );

    /**
     * Gets a field's value for the object
     *
     * @param $field
     *
     * @return mixed
     */
    public function get ( $field );

    /**
     * Gets all the values for the object
     *
     * @return mixed
     */
    public function getAll ();

    /**
     * Deletes a field from the object
     *
     * @param      $key
     * @param bool $refreshData
     *
     * @return mixed
     */
    public function delete ( $key, $refreshData = true );

    /**
     * Delete's all the fields from the object
     *
     * @return mixed
     */
    public function deleteAll ();
}
