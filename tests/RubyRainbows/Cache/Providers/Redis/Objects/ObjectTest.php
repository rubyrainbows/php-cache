<?php

use RubyRainbows\Cache\Providers\Redis\Objects\Object as Object;
use RubyRainbows\Cache\Providers\Redis\Client as Client;

class ObjectTest extends TestCase
{
    public function testConstructor()
    {
        new Object('foo_key', ['foo' => 'bar']);
        $this->assertEquals('bar', Client::hget('foo_key', 'foo'));
    }

    public function testSet()
    {
        $object = new Object('foo_key', ['foo' => 'bar']);
        $object->foo = 'bar2';
        $this->assertEquals('bar2', Client::hget('foo_key', 'foo'));
    }

    public function testGet()
    {
        $object = new Object('foo_key', ['foo' => 'bar']);
        $this->assertEquals('bar', $object->foo);
    }

    public function testGetAll()
    {
        $object = new Object('foo_key', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $object->getAll());
    }

    public function testNewAfterAlreadyCreated()
    {
        new Object('foo_key', ['foo' => 'bar']);
        new Object('foo_key');
        $this->assertEquals('bar', Client::hget('foo_key', 'foo'));
    }

    public function testDeleteAll()
    {
        $object = new Object('foo_key', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $object->getAll());
        $object->deleteAll();
        $this->assertEquals([], $object->getAll());
    }

    public function testDelete()
    {
        $object = new Object('foo_key', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $object->getAll());
        $object->delete('foo');
        $this->assertEquals(['bar' => 'foo'], $object->getAll());
    }
}