<?php

use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

it('Simple parse', function () {
    expect((new SpreadsheetDataCollector)->getTable(__DIR__.'/stubs/excel1.xlsx')->toArray())
        ->toEqual(require __DIR__.'/stubs/excel1.php');
});
