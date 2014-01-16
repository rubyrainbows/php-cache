<?php

namespace RubyRainbows\Cache\Providers\Redis\Objects;

use RubyRainbows\Cache\Providers\Redis\Objects\Tree\AddressBook;
use RubyRainbows\Cache\Providers\Redis\Objects\Tree\Node as Node;
use RubyRainbows\Cache\Providers\Redis\Client as RedisClient;

class Tree
{
    private $key     = "";
    private $root    = null;
    private $data    = null;

    /**
     * Constructs the tree
     *
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;

    }

    /**
     * Saves the tree in the cache
     */
    public function save()
    {
        $this->data = [$this->root->getData()];
        $cache      = json_encode($this->data);

        RedisClient::set($this->key, $cache);

        return $this->data;
    }

    /**
     * Caches the node address
     *
     * @param $id
     * @param $address
     */
    public function cacheNodeAddress($id, $address)
    {
        AddressBook::add($this->addressBookKey(), $id, $address);
    }

    /**
     * Makes a root node
     *
     * @param $id
     * @param array $data
     *
     * @return Node
     */
    public function makeRootNode($id,$data=[])
    {
        $this->root = new Node($id, $this, $data);
        $this->root->setAddress([0]);

        return $this->root;
    }

    /**
     * Gets the data for the tree
     *
     * @param null $id
     *
     * @return array|mixed|null
     */
    public function getData($id=null)
    {
        $address = ($id == null) ? $this->root->getAddress() : AddressBook::get($this->addressBookKey(), $id);

        return $this->getNodeBranch($address);
    }

    /**
     * Gets a node branch from the tree
     * @param $address
     *
     * @return array|mixed|null
     */
    private function getNodeBranch($address)
    {
        $data = $this->getCachedData();

        foreach ($address as $key)
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
    private function getCachedData()
    {
        if ($this->data != null)
        {
            return $this->data;
        }

        $cache = RedisClient::get($this->key);

        if ($cache == null)
        {
            return $this->save();
        }

        $this->data = json_decode($cache, true);

        return $this->data;
    }

    /**
     * Returns the address book key
     *
     * @return string
     */
    private function addressBookKey()
    {
        return $this->key . ":addresses";
    }


}