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

class CommandException extends \Exception
{
    /**
     * @param string          $function
     * @param array           $args
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct ( $function, $args, $code = 0, \Exception $previous = null )
    {
        $message = "Command '{$function}' with arguments " . join(", ", $args) . " failed!";

        parent::__construct($message, $code, $previous);
    }
}
