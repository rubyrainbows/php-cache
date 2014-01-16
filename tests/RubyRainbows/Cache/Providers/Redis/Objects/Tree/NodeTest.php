<?php

use RubyRainbows\Cache\Providers\Redis\Objects\Tree\Node as Node;

class NodeTest extends TestCase
{
    public function testNodeConstruction ()
    {
        $node = new Node("id", null, ['data', 'foo']);
        $this->assertEquals(["id" => "id", 'data', 'foo'], $node->getData());
    }

    public function testSetNodeData()
    {
        $node = new Node("id", null, ['data', 'foo']);
        $node->bar = 'foo';
        $this->assertEquals(["id" => "id", 'data', 'foo', 'bar' => 'foo'], $node->getData());
    }

    public function testAddChild()
    {
        $node = new Node("id");
        $node->addChild("id", ['foo' => 'bar']);
        $this->assertEquals(["id" => "id", 'children' => [["id" => "id", 'foo' => 'bar']]], $node->getData());
    }

    public function testNodeAddress()
    {
        $node = new Node("1");
        $this->assertEquals([0], $node->getAddress());
        $child1 = $node->addChild("2");
        $this->assertEquals([0,0], $child1->getAddress());
        $child2 = $node->addChild("3");
        $this->assertEquals([0,1], $child2->getAddress());
    }

    public function testResumeWithoutChild()
    {
        $node = new Node("1", null, ["id" => "1", "data" => "foo"]);

        $this->assertEquals(["id" => "1", "data" => "foo"], $node->getData());
    }

    public function testResumeWithChild()
    {
        $node = new Node("1", null, ["id" => "1", "data" => "foo", "children" => [["id" => "2", "foo" => "bar"]]]);

        $this->assertEquals(["id" => "1", "data" => "foo", "children" => [["id" => "2", "foo" => "bar"]]], $node->getData());

        $children = $node->getChildren();
        $child = $children[0];

        $this->assertEquals('2', $child->id);
        $this->assertEquals('bar', $child->foo);
    }

}