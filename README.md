[![Build Status](https://travis-ci.org/rubyrainbows/php-cache.png?branch=master)](https://travis-ci.org/rubyrainbows/php-cache)

# PHP Cache

PHP Cache is a simple caching library for HTML and objects.

## Installing

Add the following to your composer.json

```json
{
    "repositories": [
        {
            "url": "https://github.com/rubyrainbows/php-cache.git",
            "type": "vcs"
        }
    ],
    "require": {
        "RubyRainbows/cache": "@dev"
    }
}
```

## Setup

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

## Using Cached Objects

### Creating an Object

#### Method 1: Using CachedObject

```php
<?php

using RubyRainbows\Cache\Objects\CachedObject as CachedObject;

$object = new CachedObject('cache_key');
```

#### Method 2: Extending CachedObject

```php
<?php

using RubyRainbows\Cache\Objects\CachedObject as CachedObject;

class Extended extends CachedObject
{
    public function __construct($key, array $opts)
    {
        parent::__construct($key, $opts);
    }
}
```

### Variables
```php
<?php

using RubyRainbows\Cache\Objects\CachedObject as CachedObject;

$object = new CachedObject('cache_key');

# fill method
$object->fill(['foo' => 'bar']);

# set methods
$object->foo = 'bar';
$object->set('foo', 'bar');

# get methods
$foo = $object->foo;
$foo = $object->get('foo');
```

### Namespaces

To prevent overwriting of data with multiple objects, you can namespace your objects.  Namespacing will make your cache key
`namespace:cache_key` instead of just `cache_key`.

#### Method 1: Adding the Namespace to the Object During Creation

```php
<?php

using RubyRainbows\Cache\Objects\CachedObject as CachedObject;

$object = new CachedObject('cache_key', ['namespace' => 'foo']);
```

#### Method 2: Adding the Namespace to a Class Extending CachedObject

```php
<?php

using RubyRainbows\Cache\Objects\CachedObject as CachedObject;

class NamespaceClass extends CachedObject
{
    protected $namespace = 'foo';
}
```

## Using Trees

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

# A tree that has been saved can also be retrieved at a later point with the tree's key
$tree = Cache::tree('tree');

$root = $tree->makeRootNode(1);
$root->addChild(2, ['foo' => 'bar']);
$tree->save();

$tree = Cache::tree('tree');
```