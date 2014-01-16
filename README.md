[![Build Status](https://travis-ci.org/rubyrainbows/php-cache.png?branch=master)](https://travis-ci.org/rubyrainbows/php-cache)

# PHP Cache

PHP Cache is a simple caching library for HTML and objects.

## Installing

Add the following to your composer.json

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