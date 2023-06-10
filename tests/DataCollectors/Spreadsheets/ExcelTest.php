<?php

use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

it('Simple parse', function () {
    expect((new SpreadsheetDataCollector)->getData(__DIR__ . '/stubs/excel1.xlsx'))
        ->toEqual(require __DIR__ . '/stubs/excel1.php');
});
