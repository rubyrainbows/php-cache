<?php

/**
 * CachedObjectTest.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 * 
 */

use RubyRainbows\Cache\Objects\CachedObject;

class CachedObjectTest extends PHPUnit_Framework_TestCase
{

    public function testFillingInObject ()
    {
        $base   = $this->base();
        $obj    = new CachedObject('key', ['base' => $base]);

        $base->shouldReceive('fill')->once();
        $base->shouldReceive('get')->once()->andReturn('bar');

        $obj->fill(['foo' => 'bar']);
        $this->assertEquals('bar', $obj->foo);
    }

    public function testSettingFieldForObject()
    {
        $base   = $this->base();
        $obj    = new CachedObject('key', ['base' => $base]);

        $base->shouldReceive('get')->once()->andReturn('bar');
        $base->shouldReceive('set')->once();

        $obj->foo = 'bar';
        $this->assertEquals('bar', $obj->foo);
    }

    public function testGetAll()
    {
        $base   = $this->base();
        $obj    = new CachedObject('key', ['base' => $base]);

        $base->shouldReceive('getAll')->once()->andReturn(['foo' => 'bar']);
        $base->shouldReceive('fill')->once();

        $obj->fill(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $obj->getAll());
    }

    public function testDelete ()
    {
        $base   = $this->base();
        $obj    = new CachedObject('key', ['base' => $base]);

        $base->shouldReceive('delete')->once();

        $obj->delete('foo');
    }

    public function testDeleteAll ()
    {
        $base   = $this->base();
        $obj    = new CachedObject('key', ['base' => $base]);

        $base->shouldReceive('deleteAll')->once();

        $obj->deleteAll();
    }

    private function base()
    {
        return \Mockery::mock('\RubyRainbows\Cache\Providers\BaseObject');
    }
}
 