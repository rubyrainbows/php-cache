<?php

use RubyRainbows\Cache\Providers\Redis\Objects\Tree\Node as Node;

class NodeTest extends TestCase
{
    public function testNodeConstruction ()
    {
        $node = new Node("id", null, ['data', 'foo']);
        $this->assertEquals(['data', 'foo'], $node->getData());
    }

    public function testSetNodeData()
    {
        $node = new Node("id", null, ['data', 'foo']);
        $node->bar = 'foo';
        $this->assertEquals(['data', 'foo', 'bar' => 'foo'], $node->getData());
    }

    public function testAddChild()
    {
        $node = new Node("id");
        $node->addChild("id", 'child', ['foo' => 'bar']);
        $this->assertEquals(['child' => [['foo' => 'bar']]], $node->getData());
    }

    public function testNodeAddress()
    {
        $node = new Node("1");
        $this->assertEquals([0], $node->getAddress());
        $child1 = $node->addChild("2", 'child');
        $this->assertEquals([0,0], $child1->getAddress());
        $child2 = $node->addChild("3", 'child');
        $this->assertEquals([0,1], $child2->getAddress());
    }

}