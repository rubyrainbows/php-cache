<?php

/**
 * This file is part of the Ruby Rainbows package.
 *
 * (c) Thomas Muntaner <thomas.muntaner@rubyrainbows.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace RubyRainbows\Cache\Providers\Redis\Exceptions;

class ConnectionException extends \Exception
{
    public function __construct ( $message = "", $code = 0, \Exception $previous = null )
    {
        if ( $message == "" )
            $message = "Could not connect to redis!";

        parent::__construct($message, $code, $previous);
    }
}
