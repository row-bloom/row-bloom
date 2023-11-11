<?php

namespace RowBloom\RowBloom\DataLoaders;

/**
 * When implemented, the Factory will pass it self as param to setFactory
 * before returning the DataLoader instance
 */
interface RecursiveContract
{
    public function setFactory(Factory $factory): static;
}
