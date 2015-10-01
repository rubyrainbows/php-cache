<?php

/**
 * Tree.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Redis\Objects;

use RubyRainbows\Cache\Providers\Base\Objects\BaseTree;
use RubyRainbows\Cache\Providers\Base\Tree\AddressBook;
use RubyRainbows\Cache\Providers\Base\Tree\Node;
use RubyRainbows\Cache\Providers\Redis\RedisClient;

/**
 * Class Tree
 *
 * A tree object whose data is stored in redis.
 *
 * @package RubyRainbows\Cache\Providers\Redis\Objects
 *
 */
class RedisTree implements BaseTree
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var Node
     */
    private $root;

    /**
     * @var array
     */
    private $data;

    /**
     * @var RedisClient
     */
    private $client;

    /**
     * @var AddressBook
     */
    private $addressBook;

    /**
     * Constructs the tree
     *
     * @param $key
     * @param $expire
     */
    public function __construct ( $key, $expire=0 )
    {
        $this->key = $key;

        $addressBookKey = $this->key . ":addresses";

        $this->client = new RedisClient();
        $this->addressBook = new AddressBook($this->client, $addressBookKey, $expire);

        if ( $expire != 0 )
        {
            $this->client->expire($key, $expire);
        }

        $this->resume();
    }

    /**
     * Saves the tree in the cache
     */
    public function save ()
    {
        $this->data = [$this->root->getData()];
        $cache = json_encode($this->data);

        $this->client->set($this->key, $cache);

        return $this->data;
    }

    /**
     * Caches the node address
     *
     * @param $id
     * @param $address
     *
     * @return mixed|void
     */
    public function cacheNodeAddress ( $id, $address )
    {
        $this->addressBook->add($id, $address);
    }

    /**
     * Makes a root node
     *
     * @param       $id
     * @param array $data
     *
     * @return Node
     */
    public function makeRootNode ( $id, $data = [] )
    {
        $this->root = new Node($id, $this, $data);
        $this->root->setAddress([0]);

        return $this->root;
    }

    /**
     * Returns an array of the tree's data from the
     * specified id. If the id is null, the array starts
     * at the root.
     *
     * @param string $id
     *
     * @return array|mixed|null
     */
    public function toArray ( $id = null )
    {
        $address = ($id == null) ? $this->root->getAddress() : $this->addressBook->get($id);

        return $this->getNodeBranch($address);
    }

    /**
     * Returns an array of ids from the nodes in the branch
     *
     * @param $id
     *
     * @return array
     */
    public function branch ( $id )
    {
        $nodes = $this->toArray($id);
        $ids = [$nodes['id']];

        if ( array_key_exists('children', $nodes) )
        {
            foreach ( $nodes['children'] as $child )
            {
                $child_ids = $this->branch($child['id']);
                $ids = array_merge($ids, $child_ids);
            }
        }

        return $ids;
    }

    /**
     * Gets a node branch from the tree
     *
     * @param $address
     *
     * @return array|mixed|null
     */
    private function getNodeBranch ( $address )
    {
        $data = $this->getCachedData();

        foreach ( $address as $key )
        {
            $data = array_key_exists('children', $data) ? $data['children'][$key] : $data[$key];
        }

        return $data;
    }

    /**
     * Gets the cached data
     *
     * @return array|mixed|null
     */
    private function getCachedData ()
    {
        if ( $this->data != null )
        {
            return $this->data;
        }

        $cache = $this->client->get($this->key);

        if ( $cache == null && $this->root != null )
        {
            return $this->save();
        }

        $this->data = json_decode($cache, true);

        return $this->data;
    }

    /**
     * Returns the root node
     *
     * @return Node
     */
    public function getRoot ()
    {
        return $this->root;
    }

    /**
     * Returns whether or not the tree is empty
     *
     * @return boolean
     */
    public function isEmpty ()
    {
        return ($this->getCachedData() == null);
    }

    /**
     * Resumes a tree back to its previous state
     */
    private function resume ()
    {
        $cache = $this->getCachedData();

        if ( $cache != null )
        {
            $this->root = new Node($cache[0]['id'], $this, $cache[0]);
            $this->root->resume();
        }
    }
}
