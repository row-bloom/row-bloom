<?php

use RowBloom\RowBloom\DataLoaders\JsonDataLoader;

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

    expect((new JsonDataLoader)->getTable($file)->toArray())->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});
