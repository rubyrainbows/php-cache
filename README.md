[![Build Status](https://travis-ci.org/rubyrainbows/php-cache.png?branch=master)](https://travis-ci.org/rubyrainbows/php-cache)

# PHP Cache

PHP Cache is a simple caching library for HTML and objects.

## Installing

Add the following to your composer.json

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "RubyRainbows/cache",
                "version": "master",
                "source": {
                    "url": "https://github.com/rubyrainbows/php-cache.git",
                    "type": "git",
                    "reference": "master"
                }
            }
        }
    ],
    "require": {
        "RubyRainbows/cache": "master"
    }
}
```

## Using

### Setup

**Notice:** *Currently, only a redis cache is supported.*

```php
<?php

using RubyRainbows\Cache;

Cache::setup(
    Cache::REDIS_CACHE,
    [
        'scheme'    => 'tcp',
        'host'      => 'localhost',
        'database'  => 0
    ]
);
```

### Cached Objects

```php
<?php

using RubyRainbows\Cache;

# cache setup

# make a cached object with no data
$object = Cache::object('cache_key');

# make a cached object with data
$object = Cache::object('cache_key', ['field' => 'value']);

# getting a value from a cached object
$field = $object->field;

# setting a value to a field
$object->random_field = "foo";

# deleting a field from a cached object
$object->delete('random_field');

$deleting all fields from a cached object
$object->deleteAll();
```

### Trees

```php
<?php

using RubyRainbows\Cache;

# cache setup

# making a tree
$tree = Cache::tree('key');

# creating a root node
#
# This function makeRootNode($id,$data) takes the param of id so that the node can be easily accessed in the future
$root = $tree->makeRootNode(1);

# you can also pass field data to the root as well
$root = $tree->makeRootNode(1, ['field' => 'foo']);

# The root node can add child nodes to itself
#
# This function addChild($id,$data) takes the param of id so that the node can be easily accessed in the future
$root->addChild(2);

# As with the root node, you can also add field data to the node in the addChild() function
$root->addChild(2, ['foo' => 'bar']);

# Saving a tree to the cache
$tree->save();

# Getting the data array from the cache (staring from root node)
$tree->getData();

# You can also get the data array starting from any node by supplying its id to the getData() function
$tree->getData(1);

```