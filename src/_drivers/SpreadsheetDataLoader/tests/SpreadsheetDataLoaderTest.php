<?php

use RowBloom\RowBloom\Types\TableLocation;
use RowBloom\SpreadsheetDataLoader\SpreadsheetDataLoader;

it('parses', function () {
    $DataLoader = new SpreadsheetDataLoader;

    // ! Cannot mock File IO, Phpspreadsheet requires file path and doesn't take a raw string
    expect($DataLoader->getTable(new TableLocation(__DIR__.'/stubs/1csv.csv'))->toArray())->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});
