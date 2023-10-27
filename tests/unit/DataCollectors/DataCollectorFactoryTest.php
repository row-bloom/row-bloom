<?php

use RowBloom\RowBloom\DataCollectors\DataCollectorFactory;
use RowBloom\RowBloom\DataCollectors\Json\JsonDataCollector;

it('factorizes')
    ->expect(app()->make(DataCollectorFactory::class)->makeFromPath(__DIR__.'/../../stubs/foo.json'))
    ->toBeInstanceOf(JsonDataCollector::class);
