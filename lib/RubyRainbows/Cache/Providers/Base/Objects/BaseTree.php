<?php

/**
 * BaseTree.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Base\Objects;

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
     * @return mixed
     */
    public function save ();

    /**
     * Caches a node address
     *
     * @param $id
     * @param $address
     *
     * @return mixed
     */
    public function cacheNodeAddress ( $id, $address );

    /**
     * Makes a root node
     *
     * @param       $id
     * @param array $data
     *
     * @return mixed
     */
    public function makeRootNode ( $id, $data = [] );

    /**
     * Returns an array of the tree's data from the
     * specified id. If the id is null, the array starts
     * at the root.
     *
     * @param string $id
     *
     * @return mixed
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
     * @return mixed
     */
    public function branch ( $id );
}
