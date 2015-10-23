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
use RubyRainbows\Cache\Providers\Redis\Exceptions\CommandException;
use RubyRainbows\Cache\Providers\Redis\Exceptions\ConnectionException;
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
     * @param RedisClient $client
     * @param string      $key
     * @param integer     $expire
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function __construct ( RedisClient $client, $key, $expire = 0 )
    {
        $this->client = $client;
        $this->key = $key;
        $this->data = [];

        $addressBookKey = $this->key . ":addresses";

        $this->addressBook = new AddressBook($this->client, $addressBookKey, $expire);

        if ( $expire != 0 )
        {
            $this->client->expire($key, $expire);
        }

        $this->resume();
    }

    /**
     * Saves the tree in the cache
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function save ()
    {
        if ( $this->root == null )
            return false;

        $this->data = [$this->root->getData()];
        $cache = json_encode($this->data);

        return $this->client->set($this->key, $cache);
    }

    /**
     * Caches the node address
     *
     * @param string $id
     * @param string $address
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function cacheNodeAddress ( $id, $address )
    {
        return $this->addressBook->add($id, $address);
    }

    /**
     * Makes a root node
     *
     * @param string $id
     * @param array  $data
     *
     * @return Node
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function makeRootNode ( $id, $data = [] )
    {
        $this->root = new Node($id, $this, $data);
        $this->root->setAddress([0]);

        $this->save();

        return $this->root;
    }

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
    public function toArray ( $id = null )
    {
        $address = ($id == null) ? $this->root->getAddress() : $this->addressBook->get($id);

        return $this->getNodeBranch($address);
    }

    /**
     * Returns an array of ids from the nodes in the branch
     *
     * @param string $id
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
        return ($this->data == []);
    }

    /**
     * Gets a node branch from the tree
     *
     * @param array $address
     *
     * @return array
     */
    private function getNodeBranch ( $address )
    {
        $data = $this->data;

        foreach ( $address as $key )
        {
            $data = array_key_exists('children', $data) ? $data['children'][$key] : $data[$key];
        }

        return $data;
    }

    /**
     * Gets the cached data
     *
     * @return array
     */
    private function getCachedData ()
    {
        $cache = $this->client->get($this->key);

        return json_decode($cache, true);
    }

    /**
     * Resumes a tree back to its previous state
     */
    private function resume ()
    {
        $this->data = $this->getCachedData();

        if ( $this->data != [] )
        {
            $this->root = new Node($this->data[0]['id'], $this, $this->data[0]);
            $this->root->resume();
        }
    }
}
