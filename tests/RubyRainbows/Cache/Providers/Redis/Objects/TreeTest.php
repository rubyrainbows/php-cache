<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use RubyRainbows\Cache\Providers\Redis\Objects\RedisTree;

class TreeTest extends TestCase
{
    public function testRootNode ()
    {
        $tree = new RedisTree($this->client, 'key');
        $root = $tree->makeRootNode("id");

        $this->assertNotNull($root);
        $this->assertEquals([0], $root->getAddress());
    }

    public function testCacheOfRootNodeAddress ()
    {
        $tree = new RedisTree($this->client, 'key');
        $root = $tree->makeRootNode("id");

        $this->assertNotNull($root);
        $this->assertEquals([0], $root->getAddress());;
    }

    public function testIsEmpty ()
    {
        $tree = new RedisTree($this->client, 'empty_tree');
        $this->assertTrue($tree->isEmpty());

        $tree->makeRootNode("id");
        $this->assertFalse($tree->isEmpty());

        $tree = new RedisTree($this->client, 'empty_tree');
        $this->assertFalse($tree->isEmpty());
    }

    public function testGetDataWithHierarchyDepth1 ()
    {
        $tree = new RedisTree($this->client, 'key');
        $tree->makeRootNode("id", ['foo' => 'bar']);
        $this->assertEquals(["id" => "id", "foo" => "bar"], $tree->toArray());
    }

    public function testGetDataWithHierarchyDepth2 ()
    {
        $tree = new RedisTree($this->client, 'key');
        $root = $tree->makeRootNode('0', ['foo' => 'bar']);
        $root->addChild('1', ["foo" => "bar2"]);

        $expect = [
            "id" => '0',
            "foo" => "bar",
            "children" => [
                [
                    "id" => '1',
                    "foo" => "bar2"
                ]
            ]
        ];

        $this->assertEquals($expect, $tree->toArray());
    }

    public function testGetDataWithHierarchyDepth3 ()
    {
        $tree = new RedisTree($this->client, 'key');
        $root = $tree->makeRootNode('0', ['foo' => 'bar']);
        $child = $root->addChild('1', ["foo" => "bar2"]);

        $child->addChild('2', ['foo' => 'bar3']);

        $expect = [
            'id' => '0',
            'foo' => 'bar',
            'children' => [
                [
                    'id' => '1',
                    'foo' => 'bar2',
                    'children' => [
                        [
                            'id' => '2',
                            'foo' => 'bar3'
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expect, $tree->toArray());
    }

    public function testGetBranch ()
    {
        $tree = new RedisTree($this->client, 'key');
        $root = $tree->makeRootNode('0', ['foo' => 'bar']);
        $child = $root->addChild('1', ["foo" => "bar2"]);

        $child->addChild('2', ['foo' => 'bar3']);

        $expect = [
            'id' => '1',
            'foo' => 'bar2',
            'children' => [
                [
                    'id' => '2',
                    'foo' => 'bar3'
                ]
            ]
        ];

        $this->assertEquals($expect, $tree->toArray(1));
    }

    public function testTreeCaching ()
    {
        $tree = new RedisTree($this->client, 'tree');
        $root = $tree->makeRootNode('0', ['foo' => 'bar']);
        $child = $root->addChild('1', ["foo" => "bar2"]);

        $child->addChild('2', ['foo' => 'bar3']);
        $tree->save();

        $array = [
            [
                'foo' => 'bar',
                'id' => '0',
                'children' => [
                    [
                        'foo' => 'bar2',
                        'id' => '1',
                        'children' => [
                            [
                                'foo' => 'bar3',
                                'id' => '2',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $expect = json_encode($array);

        $this->assertEquals($expect, $this->client->get('tree'));
    }

    public function testTreeResume ()
    {
        $tree = new RedisTree($this->client, 'tree');
        $root = $tree->makeRootNode('1', ['foo' => 'bar']);
        $child = $root->addChild('2', ["foo" => "bar2"]);
        $child->addChild('3', ['foo' => 'bar3']);
        $tree->save();

        $expectData = $tree->toArray();

        $this->assertEquals(['1', '2', '3'], $tree->branch(1));
        $this->assertEquals(['2', '3'], $tree->branch(2));
        $this->assertEquals(['3'], $tree->branch(3));

        $tree = new RedisTree($this->client, 'tree');

        $root = $tree->getRoot();
        $child = $root->getChildren()[0];
        $grandchild = $child->getChildren()[0];

        $this->assertEquals([0], $root->getAddress());
        $this->assertEquals([0, 0], $child->getAddress());
        $this->assertEquals([0, 0, 0], $grandchild->getAddress());

        $this->assertEquals(['1', '2', '3'], $tree->branch(1));
        $this->assertEquals(['2', '3'], $tree->branch(2));
        $this->assertEquals(['3'], $tree->branch(3));

        $this->assertEquals($expectData, $tree->toArray());
    }

    public function testTreeBranch ()
    {
        $tree = new RedisTree($this->client, 'tree');
        $root = $tree->makeRootNode('0', ['foo' => 'bar']);
        $root->addChild('1', ["foo" => "bar2"]);
        $tree->save();

        $this->assertEquals(['0', '1'], $tree->branch('0'));
    }

}
