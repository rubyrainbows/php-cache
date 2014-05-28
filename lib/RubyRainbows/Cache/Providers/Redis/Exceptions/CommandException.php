<?php

namespace RubyRainbows\Cache\Providers\Redis\Exceptions;

class CommandException extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}