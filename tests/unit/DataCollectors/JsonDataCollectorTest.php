<?php

use ElaborateCode\RowBloom\DataCollectors\DataCollector;
use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\DataCollectors\Json\JsonDataCollector;
use ElaborateCode\RowBloom\Fs\File;

it('parses', function () {
    $dataCollector = app()->make(DataCollectorFactory::class)->make(DataCollector::Json);

    expect($dataCollector)->toBeInstanceOf(JsonDataCollector::class);

    $mock = Mockery::mock(File::class);

    $mock->shouldReceive('mustExist')->andReturns($mock);
    $mock->shouldReceive('mustBeReadable')->andReturns($mock);
    $mock->shouldReceive('mustBeFile')->andReturns($mock);
    $mock->shouldReceive('mustBeExtension')->andReturns($mock);
    $mock->shouldReceive('readFileContent')->andReturns('[
        {
            "a": 2,
            "b": 2
        },
        {
            "a": 3,
            "b": 3
        }
    ]');

    expect(
        $dataCollector->getTable($mock)->toArray()
    )->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});
