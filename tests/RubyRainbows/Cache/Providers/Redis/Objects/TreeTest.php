<?php

use RubyRainbows\Cache\Providers\Redis\Objects\Tree as Tree;
use RubyRainbows\Cache\Providers\Redis\Objects\Tree\Node as Node;
use RubyRainbows\Cache\Providers\Redis\Client as Client;

class TreeTest extends TestCase
{
    public function testRootNode()
    {
        $tree = new Tree('key');
        $root = $tree->makeRootNode("id");

        $this->assertNotNull($root);
        $this->assertEquals([0], $root->getAddress());
    }

    public function testCacheOfRootNodeAddress()
    {
        $tree = new Tree('key');
        $root = $tree->makeRootNode("id");

        $this->assertNotNull($root);
        $this->assertEquals([0], $root->getAddress());;
        $this->assertEquals([0], Tree\AddressBook::get('key:addresses', "id"));
    }

}