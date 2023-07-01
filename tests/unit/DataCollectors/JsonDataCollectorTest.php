<?php

use ElaborateCode\RowBloom\DataCollectors\DataCollector;
use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\DataCollectors\Json\JsonDataCollector;

it('parses', function () {
    $dataCollector = DataCollectorFactory::getInstance()->make(DataCollector::Json);

    expect($dataCollector)->toBeInstanceOf(JsonDataCollector::class);

    expect(
        $dataCollector->getTable(__DIR__.'/../../stubs/2json.json')->toArray()
    )->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});