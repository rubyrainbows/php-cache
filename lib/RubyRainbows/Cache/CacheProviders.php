<?php

/**
 * CacheProviders.php
 *
 * @author      Thomas Muntanerd
 * @version     1.0.0
 */

namespace RubyRainbows\Cache;

use MyCLabs\Enum\Enum;

/**
 * Class CacheProviders
 *
 * Enum for selecting cache provider
 *
 * @package RubyRainbows\Cache
 *
 */
class CacheProviders extends Enum
{
    const REDIS = 1;
}
