<?php

/**
 * BaseTree.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 * 
 */

namespace RubyRainbows\Cache\Providers\Base\Objects;

/**
 * Interface BaseTree
 *
 * A tree that interacts directly with a cache store
 *
 * @package RubyRainbows\Cache\Providers\Base\Objects
 *
 */
interface BaseTree
{
    /**
     * Constructs the tree
     * @param $key
     *
     */
    public function __construct ($key);

    /**
     * Saves the tree
     *
     * @return mixed
     *
     */
    public function save();

    /**
     * Caches a node address
     *
     * @param $id
     * @param $address
     *
     * @return mixed
     *
     */
    public function cacheNodeAddress($id, $address);

    /**
     * Makes a root node
     *
     * @param       $id
     * @param array $data
     *
     * @return mixed
     */
    public function makeRootNode($id, $data=[]);

    /**
     * Gets data from the tree based on tree id
     *
     * @param null $id
     *
     * @return mixed
     *
     */
    public function getData($id=null);

    /**
     * Returns an array of the id of the node and its children
     *
     * @param $id
     *
     * @return mixed
     *
     */
    public function branch($id);

} 