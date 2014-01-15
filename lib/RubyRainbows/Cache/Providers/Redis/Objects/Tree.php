<?php

namespace RubyRainbows\Cache\Providers\Redis\Objects;

use RubyRainbows\Cache\Providers\Redis\Objects\Tree\AddressBook;
use RubyRainbows\Cache\Providers\Redis\Objects\Tree\Node as Node;


class Tree
{
    private $key     = "";
    private $root    = null;
    private $data    = null;

    public function __construct($key)
    {
        $this->key = $key;

    }

    public function cacheNodeAddress($id, $address)
    {
        AddressBook::add($this->key . ":addresses", $id, $address);
    }

    public function makeRootNode($id)
    {
        $this->root = new Node($id, $this);
        $this->root->setAddress([0]);

        return $this->root;
    }


}