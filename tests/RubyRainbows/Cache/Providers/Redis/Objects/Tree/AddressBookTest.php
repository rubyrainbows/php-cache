<?php

use RubyRainbows\Cache\Providers\Redis\Objects\Tree\AddressBook;
use RubyRainbows\Cache\Providers\Redis\Client as Client;

class AddressBookTest extends TestCase
{
    public function testAdd()
    {
        AddressBook::add("test_key", "id", [0]);
        $this->assertEquals('[0]', Client::hget("test_key", "id"));
    }

    public function testGet()
    {
        AddressBook::add("test_key", "id", [0,1]);
        $this->assertEquals([0,1], AddressBook::get("test_key", "id"));
    }
}