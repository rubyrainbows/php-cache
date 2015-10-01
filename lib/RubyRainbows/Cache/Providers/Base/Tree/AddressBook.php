<?php

/**
 * AddressBook.php
 *
 * @author      Thomas Muntaner
 * @version     1.0.0
 */

namespace RubyRainbows\Cache\Providers\Base\Tree;

use RubyRainbows\Cache\Providers\Base\BaseClient;

/**
 * Class AddressBook
 *
 * Stores the address of a trees node for quick access.
 *
 * @package RubyRainbows\Cache\Providers\Redis\Objects\Tree
 *
 */
class AddressBook
{
    /**
     * @var BaseClient
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    /**
     * @param     $client BaseClient
     * @param     $key
     * @param int $expire
     */
    public function __construct ( $client, $key, $expire=0 )
    {
        $this->client = $client;
        $this->key = $key;

        if ( $expire != 0 )
            $this->client->expire($key, $expire);
    }

    /**
     * Adds an address to the address book
     *
     * @param       $id
     * @param array $address
     */
    public function add ( $id, array $address = [] )
    {
        $this->client->setHashValue($this->key, $id, json_encode($address));
    }

    /**
     * Gets an address from the address book
     *
     * @param $id
     *
     * @return mixed
     */
    public function get ( $id )
    {
        return json_decode($this->client->getHashValue($this->key, $id), false);
    }
}
