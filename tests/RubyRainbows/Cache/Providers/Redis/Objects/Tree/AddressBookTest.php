<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use RubyRainbows\Cache\Providers\Base\Tree\AddressBook;

class AddressBookTest extends TestCase
{
    private $addressBook;

    public function setUp ()
    {
        parent::setUp();

        $this->addressBook = new AddressBook( $this->client, 'test_key');
    }
    public function testAdd ()
    {
        $this->addressBook->add("id", [0]);
        $this->assertEquals('[0]', $this->client->getHashValue("test_key", "id"));
    }

    public function testGet ()
    {
        $this->addressBook->add("id", [0, 1]);
        $this->assertEquals([0, 1], $this->addressBook->get("id"));
    }
}
