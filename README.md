[![Latest Stable Version](https://poser.pugx.org/rubyrainbows/cache/version.svg)](https://packagist.org/packages/rubyrainbows/cache)
[![Total Downloads](https://poser.pugx.org/rubyrainbows/cache/downloads.svg)](https://packagist.org/packages/rubyrainbows/cache)
[![Build Status](https://travis-ci.org/rubyrainbows/php-cache.png?branch=master)](https://travis-ci.org/rubyrainbows/php-cache)

# PHP Cache

PHP Cache is a simple caching library for HTML and objects.

## Installing

Add the following to your composer.json

```json
{
    "require": {
        "rubyrainbows/cache": "~1.1.0"
    }
}
```

## Setup

**Notice:** *I built this library agnostic to different cache providers, however, since I mainly use redis, redis is the only supported cache.*

```php
<?php

use RubyRainbows\Cache\Cache;

// a redis cache at localhost and database of 0
$cache = new Cache ();

// a more specified cache
$cache = new Cache( Cache::REDIS_CACHE, 'tcp://127.0.0.1:6379?database=0');
```

## Using Cached Objects

A cached object is a PHP object that has its values stored in the cache for easy storage and retrieval. In redis, this
is done through the hash data type. When you recreate the object with the correct cache key, the object will be automatically
resumed to its previous state.

```php
<?php

use RubyRainbows\Cache\Cache;

$cache = new Cache();

// a basic cached object
$object = $cache->createObject('cache_key'); 

// a cached object that expires in 10 seconds
$object = $cache->createObject('cache_key', 10);

// a cached object with a value foo that equals bar.
$object = $cache->createObject('cache_key', 0, ['foo' => 'bar']);

// filling the object with an array of variables
$object->fill(['foo' => 'bar']);

// setting a variable directly
$object->set('foo', 'bar');

// getting a variable
$foo = $object->get('foo');
```

## Using Cached Trees

Cached trees are a way of having tree objects stored in the cache. These trees are also automatically resumed to the 
previous state if created with the same key.

```php
<?php

use RubyRainbows\Cache\Cache;

$cache = new Cache();

// a basic tree
$tree = $cache->createTree('cached_key');

// a tree that expires after 10 seconds
$tree = $cache->createTree('cached_key', 10);

// a root node with the id of 1 ('the id can also be a string')
$root = $tree->makeRootNode(1);

// a root node with the id of 1 and a value of foo that equals bar
$root = $tree->makeRootNode(1, ['foo' => 'bar']);

// set the value foo to bar
$root->set('foo', 'bar');

// get the value stored under bar
$root->get('foo');

// add a new child
$node = $root->addChild(2);

// add a new child with the value foo equaling bar
$node = $root->addChild(2, ['foo' => 'bar']);

// saving the tree
$tree->save();

// turns the tree to an array starting from root
$tree->getArray();

// turns the tree to an array starting at the child with the id of 2
$tree->getArray(2);
```
