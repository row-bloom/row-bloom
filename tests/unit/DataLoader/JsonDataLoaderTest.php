<?php

use Mockery\Mock;
use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\Json\JsonDataLoader;
use RowBloom\RowBloom\Fs\File;

it('parses', function () {
    $DataLoader = app()->make(DataLoaderFactory::class)->make(JsonDataLoader::NAME);

    expect($DataLoader)->toBeInstanceOf(JsonDataLoader::class);

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

    expect($DataLoader->getTable($file)->toArray())->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});

function mockJsonFile(): File|Mock
{
    /** @var File|Mock */
    $file = Mockery::mock(File::class);

    $file->shouldReceive('mustExist')->andReturns($file);
    $file->shouldReceive('mustNotExist')->andReturns($file);
    $file->shouldReceive('mustBeDir')->andReturns($file);
    $file->shouldReceive('mustBeFile')->andReturns($file);
    $file->shouldReceive('mustBeWritable')->andReturns($file);
    $file->shouldReceive('mustBeReadable')->andReturns($file);
    $file->shouldReceive('mustBeExtension')->andReturns($file);

    return $file;
}
