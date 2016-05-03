<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
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
