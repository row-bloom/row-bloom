<?php

use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\RowBloomException;

test('makeFromPath')
    ->expect(app()->make(DataLoaderFactory::class)->makeFromPath(mockJsonFile()))
    ->toBeInstanceOf(JsonDataLoader::class);

it('throws when extension is not supported')
    ->expect(fn () => app()->make(DataLoaderFactory::class)->makeFromPath(__FILE__))
    ->throws(RowBloomException::class);
