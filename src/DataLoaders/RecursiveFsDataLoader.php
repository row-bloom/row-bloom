<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\RowBloomException;

abstract class RecursiveFsDataLoader implements FsContract
{
    protected Factory $factory;

    public function __construct(protected ?Config $config = null)
    {
    }

    public function setFactory(Factory $factory): static
    {
        $this->factory = $factory;

        return $this;
    }

    protected function getFactory(): Factory
    {
        if (! isset($this->factory)) {
            throw new RowBloomException('Use setFactory to set factory on:'.static::class);
        }

        return $this->factory;
    }
}
