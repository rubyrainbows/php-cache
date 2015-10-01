<?php

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
