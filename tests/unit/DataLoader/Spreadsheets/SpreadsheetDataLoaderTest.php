<?php

use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\Spreadsheets\SpreadsheetDataLoader;

it('parses', function () {
    $DataLoader = app()->make(DataLoaderFactory::class)->make(SpreadsheetDataLoader::NAME);

    expect($DataLoader)->toBeInstanceOf(SpreadsheetDataLoader::class);

    // ! Cannot mock File IO, Phpspreadsheet requires file path and doesn't take a raw string
    expect($DataLoader->getTable(__DIR__.'/../../../stubs/1csv.csv')->toArray())->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});
