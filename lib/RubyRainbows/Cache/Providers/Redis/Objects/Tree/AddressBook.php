<?php
namespace RubyRainbows\Cache\Providers\Redis\Objects\Tree;

use RubyRainbows\Cache\Providers\Redis\Client as Client;

class AddressBook
{
    public static function add($key, $id, array $address=[])
    {
        Client::hset($key,$id, json_encode($address));
    }

    public static function get($key, $id)
    {
        return json_decode(Client::hget($key, $id), false);
    }
}