<?php

use Mockery\Mock;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Types\TableLocation;

it('parses', function () {
    $file = mockJsonFile();

    $file->shouldReceive('readFileContent')->andReturns('[
        {
            "a": 2,
            "b": 2
        },
        {
            "a": 3,
            "b": 3
        }
    ]');

    /** @var TableLocation|Mock */
    $location = Mockery::mock(TableLocation::class);

    $location->shouldReceive('getFile')->andReturns($file);

    expect((new JsonDataLoader)->getTable($location)->toArray())->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});
