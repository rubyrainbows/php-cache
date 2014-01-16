<?php

namespace RubyRainbows\Cache\Providers\Redis\Objects\Tree;

use RubyRainbows\Cache\Providers\Redis\Objects\Tree as Tree;

class Node
{
    private $data       = null;
    private $id         = null;
    private $tree       = null;
    private $children   = [];
    private $address    = [];


    /**
     * Constructs the node
     *
     * @param $id
     * @param Tree $tree
     * @param array $data
     */
    public function __construct($id, Tree $tree=null, $data=[])
    {
        $this->data         = $data;
        $this->data['id']   = $id;
        $this->address      = [0];
        $this->id           = $id;
        $this->tree         = $tree;
    }

    /**
     * Gets the node data
     *
     * @return array|null
     */
    public function getData()
    {
        $data = $this->data;

        foreach ($this->children as $key => $value)
        {
            foreach ($this->children[$key] as $child)
            {
                $data[$key][] = $child->getData();
            }
        }

        return $data;
    }

    /**
     * Sets the node value for a field
     *
     * @param $field
     * @param $value
     */
    public function __set($field, $value)
    {
        $this->data[$field] = $value;
    }

    /**
     * Adds a child to the node
     */
    public function addChild($id, $type, $data=[])
    {
        $child      = new Node($id, $this->tree, $data);
        $address    = $this->address;
        $address[]                  = (array_key_exists($type, $this->children)) ? count($this->children[$type]) : 0;
        $this->children[$type][]    = $child;

        $child->setAddress($address);

        return $child;
    }

    /**
     * Sets a node address
     *
     * @param $address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        if ( $this->tree != null)
        {
            $this->tree->cacheNodeAddress($this->id, $address);
        }
    }

    /**
     * Gets a node's address
     *
     * @return array
     */
    public function getAddress()
    {
        return $this->address;
    }

}