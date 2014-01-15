<?php
use RubyRainbows\Cache\Providers\Redis\Client as RedisClient;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        RedisClient::clear();
    }

    public function tearDown()
    {

    }
}