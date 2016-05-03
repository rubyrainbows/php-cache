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

use RubyRainbows\Cache\Providers\Base\BaseClient;
use RubyRainbows\Cache\Providers\Redis\Exceptions\CommandException;
use RubyRainbows\Cache\Providers\Redis\Exceptions\ConnectionException;

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
     * @param BaseClient $client
     * @param string     $key
     * @param int        $expire
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function __construct ( $client, $key, $expire = 0 )
    {
        $this->client = $client;
        $this->key = $key;

        if ( $expire != 0 )
            $this->client->expire($key, $expire);
    }

    /**
     * Adds an address to the address book
     *
     * @param string $id
     * @param array  $address
     *
     * @return boolean
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function add ( $id, array $address = [] )
    {
        return $this->client->setHashValue($this->key, $id, json_encode($address));
    }

    /**
     * Gets an address from the address book
     *
     * @param $id
     *
     * @return array
     *
     * @throws CommandException
     * @throws ConnectionException
     */
    public function get ( $id )
    {
        return json_decode($this->client->getHashValue($this->key, $id), false);
    }
}
