<?php

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
