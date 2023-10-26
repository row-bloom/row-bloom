<?php

namespace RowBloom\RowBloom\Drivers;

use RowBloom\RowBloom\Support;

abstract class BaseDriverFactory
{
    public function __construct(protected Support $support)
    {
    }

    abstract public function make(string $driver): object;
}
