<?php

use RowBloom\RowBloom\DataCollectors\DataCollectorFactory;
use RowBloom\RowBloom\DataCollectors\Json\JsonDataCollector;
use RowBloom\RowBloom\RowBloomException;

test('makeFromPath')
    ->expect(app()->make(DataCollectorFactory::class)->makeFromPath(__DIR__.'/../../stubs/foo.json'))
    ->toBeInstanceOf(JsonDataCollector::class);

it('throws when extension is not supported')
    ->expect(fn () => app()->make(DataCollectorFactory::class)->makeFromPath(__FILE__))
    ->throws(RowBloomException::class);
