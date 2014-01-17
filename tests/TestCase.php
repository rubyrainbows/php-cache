<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RubyRainbows\Cache\Providers\Redis\Client as RedisClient;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        RedisClient::clear();
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}