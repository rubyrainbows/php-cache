<?php

use RubyRainbows\Cache\Providers\Redis\Objects\RedisObject;

class ObjectTest extends TestCase
{
    public function testConstructor ()
    {
        new RedisObject($this->client, 'foo_key', ['foo' => 'bar']);
        $this->assertEquals('bar', $this->client->getHashValue('foo_key', 'foo'));
    }

    public function testSet ()
    {
        $object = new RedisObject($this->client, 'foo_key', ['foo' => 'bar']);
        $object->set('foo', 'bar2');
        $this->assertEquals('bar2', $this->client->getHashValue('foo_key', 'foo'));
    }

    public function testGet ()
    {
        $object = new RedisObject($this->client, 'foo_key', ['foo' => 'bar']);
        $this->assertEquals('bar', $object->get('foo'));
    }

    public function testGetAll ()
    {
        $object = new RedisObject($this->client, 'foo_key', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $object->getAll());
    }

    public function testNewAfterAlreadyCreated ()
    {
        new RedisObject($this->client, 'foo_key', ['foo' => 'bar']);
        new RedisObject($this->client, 'foo_key');
        $this->assertEquals('bar', $this->client->getHashValue('foo_key', 'foo'));
    }

    public function testDeleteAll ()
    {
        $object = new RedisObject($this->client, 'foo_key', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $object->getAll());
        $object->deleteAll();
        $this->assertEquals([], $object->getAll());
    }

    public function testDelete ()
    {
        $object = new RedisObject($this->client, 'foo_key', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $object->getAll());
        $object->delete('foo');
        $this->assertEquals(['bar' => 'foo'], $object->getAll());
    }
}
