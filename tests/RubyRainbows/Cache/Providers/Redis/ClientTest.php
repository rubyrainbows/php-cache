<?php

class ClientTest extends TestCase
{
    public function testGetSet ()
    {
        $this->client->set('foo_key', 'foo_value');
        $this->assertEquals('foo_value', $this->client->get('foo_key'));
    }

    public function testClearingOfDatabase ()
    {
        $this->client->set('foo_key', 'foo_value');
        $this->assertEquals('foo_value', $this->client->get('foo_key'));
        $this->client->clear();
        $this->assertEquals(null, $this->client->get('foo_key'));
    }
}
