<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use RubyRainbows\Cache\Providers\Redis\RedisClient as RedisClient;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RedisClient
     */
    protected $client;

    public function setUp ()
    {
        $this->client = new RedisClient();
        $this->client->clear();
    }

    public function tearDown ()
    {
        \Mockery::close();
    }
}
