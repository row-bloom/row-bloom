<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Utils\ValidateDriverConcern;

abstract class BaseDriverFactory
{
    use ValidateDriverConcern;

    public function __construct(protected Support $support)
    {
    }

    abstract public function make(string $driver): object;
}
