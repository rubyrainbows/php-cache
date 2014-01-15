<?php

use RubyRainbows\Cache\Providers\Redis\Client as Client;

class ClientTest extends TestCase
{
    public function testGetSet()
    {
        Client::set('foo_key', 'foo_value');
        $this->assertEquals('foo_value', Client::get('foo_key'));
    }

    public function testClearingOfDatabase()
    {
        Client::set('foo_key', 'foo_value');
        $this->assertEquals('foo_value', Client::get('foo_key'));
        Client::clear();
        $this->assertEquals(null, Client::get('foo_key'));
    }
}