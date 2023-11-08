<?php

namespace RowBloom\RowBloom;

use Psr\Container\ContainerInterface;
use RowBloom\RowBloom\Utils\ValidateDriverConcern;

abstract class BaseDriverFactory
{
    use ValidateDriverConcern;

    public function __construct(protected ContainerInterface $container, protected Support $support)
    {
    }

    abstract public function make(string $driver): object;
}
