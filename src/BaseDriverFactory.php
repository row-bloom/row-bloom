<?php

namespace RowBloom\RowBloom;

use Psr\Container\ContainerInterface;
use RowBloom\RowBloom\Utils\ValidateDriverConcern;

abstract class BaseDriverFactory
{
    use ValidateDriverConcern;

    public function __construct(
        protected Support $support,
        protected ?ContainerInterface $container = null
    ) {
    }

    abstract public function make(string $driver): object;

    public function instantiate(string $className): object
    {
        return is_null($this->container) ? new $className : $this->container->get($className);
    }
}
