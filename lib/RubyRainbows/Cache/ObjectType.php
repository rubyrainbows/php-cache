<?php

/**
 * ObjectType.php
 *
 * @author      Thomas Muntaner
 * @copyright   2014 Thomas Muntaner
 * @version     1.0.0
 * 
 */

namespace RubyRainbows\Cache;

use MyCLabs\Enum\Enum;

class ObjectType extends Enum
{
    const OBJECT    = 1;
    const TREE      = 2;
}