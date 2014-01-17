<?php

/**
 * CachedTreeTest.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 * 
 */

use RubyRainbows\Cache\Objects\CachedTree;

class CachedTreeTest extends TestCase
{
    public function testSave()
    {
        $base = $this->base();
        $tree = new CachedTree('key', ['base' => $base]);

        $base->shouldReceive('save')->once();

        $tree->save();
    }

    public function testCacheNodeAddress()
    {
        $base = $this->base();
        $tree = new CachedTree('key', ['base' => $base]);

        $base->shouldReceive('cacheNodeAddress')->once();

        $tree->cacheNodeAddress('key', [0]);
    }

    public function testMakeRootNode()
    {
        $base = $this->base();
        $tree = new CachedTree('key', ['base' => $base]);

        $base->shouldReceive('makeRootNode')->once();

        $tree->makeRootNode('root');
    }

    public function testGetData()
    {
        $base = $this->base();
        $tree = new CachedTree('key', ['base' => $base]);

        $base->shouldReceive('getData')->once();

        $tree->getData();
    }

    public function testBranch()
    {
        $base = $this->base();
        $tree = new CachedTree('key', ['base' => $base]);

        $base->shouldReceive('branch')->once();

        $tree->branch(1);
    }

    private function base()
    {
        return \Mockery::mock('\RubyRainbows\Cache\Providers\BaseTree');
    }
}
 