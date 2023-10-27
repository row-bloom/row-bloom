<?php

use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\Json\JsonDataLoader;
use RowBloom\RowBloom\RowBloomException;

test('makeFromPath')
    ->expect(app()->make(DataLoaderFactory::class)->makeFromPath(__DIR__.'/../../stubs/foo.json'))
    ->toBeInstanceOf(JsonDataLoader::class);

it('throws when extension is not supported')
    ->expect(fn () => app()->make(DataLoaderFactory::class)->makeFromPath(__FILE__))
    ->throws(RowBloomException::class);
