<?php

namespace RubyRainbows\Cache\Objects;

interface CachedObject
{
    public function __construct($key, $data=[]);

    public function __set($field, $value);

    public function __get($field);

    public function getAll();

    public function delete($key, $refreshData=true);

    public function deleteAll();
}