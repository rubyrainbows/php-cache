<?php

/**
 * BaseTree.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Base\Objects;
use RubyRainbows\Cache\Providers\Base\Tree\Node;
use RubyRainbows\Cache\Providers\Redis\Exceptions\CommandException;
use RubyRainbows\Cache\Providers\Redis\Exceptions\ConnectionException;

/**
 * Interface BaseTree
 *
 * A tree that interacts directly with a cache store
 *
 * @package RubyRainbows\Cache\Providers\Base\Objects
 */
interface BaseTree
{
    /**
     * Saves the tree
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function save ();

    /**
     * Caches a node address
     *
     * @param string $id
     * @param array $address
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function cacheNodeAddress ( $id, $address );

    /**
     * Makes a root node
     *
     * @param integer $id
     * @param array $data
     *
     * @return Node
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function makeRootNode ( $id, $data = [] );

    /**
     * Returns an array of the tree's data from the
     * specified id. If the id is null, the array starts
     * at the root.
     *
     * @param string $id
     *
     * @return array
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function toArray ( $id = null );

    /**
     * Returns whether or not the tree is empty
     *
     * @return boolean
     */
    public function isEmpty ();

    /**
     * Returns an array of the id of the node and its children
     *
     * @param $id
     *
     * @return array
     */
    public function branch ( $id );
}
