<?php

namespace RowBloom\RowBloom;

use Illuminate\Container\Container;
use RowBloom\RowBloom\Utils\ValidateDriverConcern;

abstract class BaseDriverFactory
{
    use ValidateDriverConcern;

    public function __construct(protected Container $container, protected Support $support)
    {
    }

    abstract public function make(string $driver): object;
}
