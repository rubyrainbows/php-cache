<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace RubyRainbows\Cache\Providers\Base\Objects;

use RubyRainbows\Cache\Providers\Redis\Exceptions\CommandException;
use RubyRainbows\Cache\Providers\Redis\Exceptions\ConnectionException;

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
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function expire ( $expire );

    /**
     * Sets a field's value for the object
     *
     * @param string $field
     * @param string $value
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function set ( $field, $value );

    /**
     * Gets a field's value for the object
     *
     * @param string $field
     *
     * @return string
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function get ( $field );

    /**
     * Gets all the values for the object
     *
     * @return array
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function getAll ();

    /**
     * Deletes a field from the object
     *
     * @param string $key
     * @param boolean $refreshData
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function delete ( $key, $refreshData = true );

    /**
     * Delete's all the fields from the object
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function deleteAll ();
}
