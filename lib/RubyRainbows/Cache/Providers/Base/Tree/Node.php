<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace RubyRainbows\Cache\Providers\Base\Tree;

use RubyRainbows\Cache\Providers\Base\Objects\BaseTree;
use RubyRainbows\Cache\Providers\Redis\Exceptions\CommandException;
use RubyRainbows\Cache\Providers\Redis\Exceptions\ConnectionException;

/**
 * Class Node
 *
 * Node from a tree for storing hierarchical data.
 *
 * @package RubyRainbows\Cache\Providers\Redis\Objects\Tree
 *
 */
class Node
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $data;

    /**
     * @var BaseTree
     */
    private $tree;

    /**
     * @var array
     */
    private $address;

    /**
     * @var array
     */
    private $children;

    /**
     * Constructs the node
     *
     * @param          $id
     * @param BaseTree $tree
     * @param array $data
     */
    public function __construct ( $id, BaseTree $tree = null, $data = [] )
    {
        $this->data = $data;
        $this->data['id'] = $id;
        $this->address = [0];
        $this->id = $id;
        $this->tree = $tree;
        $this->children = [];
    }

    /**
     * Gets the node data
     *
     * @return array|null
     */
    public function getData ()
    {
        $data = $this->data;

        foreach ( $this->children as $child )
        {
            /**
             * @var $child Node
             */
            $data['children'][] = $child->getData();
        }

        return $data;
    }

    /**
     * Returns the id of the node
     *
     * @return string
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * Sets the node value for a field
     *
     * @param $field
     * @param $value
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function set ( $field, $value )
    {
        $this->data[$field] = $value;

        if ( $this->tree != null )
        {
            $this->tree->save();
        }
    }

    /**
     * Get the node value from a field
     *
     * @param $field
     *
     * @return mixed
     */
    public function get ( $field )
    {
        return $this->data[$field];
    }

    /**
     * Adds a child to the node
     *
     * @param string $id
     * @param array $data
     * @param bool $saveToCache
     *
     * @return Node
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function addChild ( $id, $data = [], $saveToCache = true )
    {
        $child = new Node($id, $this->tree, $data);
        $address = $this->address;
        $address[] = count($this->children);

        $this->children[] = $child;

        $child->setAddress($address, $saveToCache);
        $child->resume();

        return $child;
    }

    /**
     * Sets a node address
     *
     * @param      $address
     * @param bool $saveToCache
     */
    public function setAddress ( $address, $saveToCache = true )
    {
        $this->address = $address;

        if ( $this->tree != null && $saveToCache )
        {
            $this->tree->cacheNodeAddress($this->id, $address);
        }
    }

    /**
     * Gets a node's address
     *
     * @return array
     *
     */
    public function getAddress ()
    {
        return $this->address;
    }

    /**
     * Gets the children of the node
     *
     * @return Node[]
     */
    public function getChildren ()
    {
        return $this->children;
    }

    /**
     * Resumes a node
     */
    public function resume ()
    {
        if ( array_key_exists('children', $this->data) )
        {
            $children = (array_key_exists('children', $this->data)) ? $this->data['children'] : [];
            $this->data['children'] = [];

            foreach ( $children as $child_cache )
            {
                $this->addChild($child_cache['id'], $child_cache, false);
            }
        }
    }
}
