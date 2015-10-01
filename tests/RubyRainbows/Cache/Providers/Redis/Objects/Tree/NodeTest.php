<?php


use RubyRainbows\Cache\Providers\Base\Tree\Node;

class NodeTest extends TestCase
{
    public function testNodeConstruction ()
    {
        $node = new Node("id", null, ['data', 'foo']);
        $this->assertEquals(["id" => "id", 'data', 'foo'], $node->getData());
    }

    public function testSetNodeData ()
    {
        $node = new Node("id", null, ['data', 'foo']);
        $node->set('bar', 'foo');
        $this->assertEquals(["id" => "id", 'data', 'foo', 'bar' => 'foo'], $node->getData());
    }

    public function testAddChild ()
    {
        $node = new Node("id");
        $node->addChild("id", ['foo' => 'bar']);
        $this->assertEquals(["id" => "id", 'children' => [["id" => "id", 'foo' => 'bar']]], $node->getData());
    }

    public function testNodeAddress ()
    {
        $node = new Node("1");
        $this->assertEquals([0], $node->getAddress());
        $child1 = $node->addChild("2");
        $this->assertEquals([0, 0], $child1->getAddress());
        $child2 = $node->addChild("3");
        $this->assertEquals([0, 1], $child2->getAddress());
    }

    public function testResumeWithoutChild ()
    {
        $node = new Node("1", null, ["id" => "1", "data" => "foo"]);

        $this->assertEquals(["id" => "1", "data" => "foo"], $node->getData());
    }

    public function testResumeWithChild ()
    {
        $node = new Node(1, null, ['foo' => 'bar']);
        $child = $node->addChild(2, ['foo2' => 'bar2']);
        $grandchild = $child->addChild(3, ['foo3' => 'bar3']);

        $this->assertEquals([
            'id' => 1,
            'foo' => 'bar',
            'children' => [
                [
                    'id' => 2,
                    'foo2' => 'bar2',
                    'children' => [
                        [
                            'id' => 3,
                            'foo3' => 'bar3'
                        ]
                    ]
                ]
            ]
        ], $node->getData());

        $this->assertEquals([0], $node->getAddress());
        $this->assertEquals([0, 0], $child->getAddress());
        $this->assertEquals([0, 0, 0], $grandchild->getAddress());

        $this->assertEquals('1', $node->getId());
        $this->assertEquals('bar', $node->get('foo'));
        $this->assertEquals('2', $child->getId());
        $this->assertEquals('bar2', $child->get('foo2'));

        $resumedNode = new Node(1, null, $node->getData());
        $resumedNode->resume();

        $resumedChild = $node->getChildren()[0];
        $resumedGrandchild = $resumedChild->getChildren()[0];

        $this->assertEquals($node->getData(), $resumedNode->getData());

        $this->assertEquals([0], $resumedNode->getAddress());
        $this->assertEquals([0, 0], $resumedChild->getAddress());
        $this->assertEquals([0, 0, 0], $resumedGrandchild->getAddress());

        $this->assertEquals('1', $resumedNode->getId());
        $this->assertEquals('bar', $resumedNode->get('foo'));
        $this->assertEquals('2', $resumedChild->getId());
        $this->assertEquals('bar2', $resumedChild->get('foo2'));
        $this->assertEquals('3', $resumedGrandchild->getId());
        $this->assertEquals('bar3', $resumedGrandchild->get('foo3'));

    }

}
