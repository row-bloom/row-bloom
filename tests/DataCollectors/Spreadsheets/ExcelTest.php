<?php

use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDc;

it('Simple parse', function () {
    expect((new SpreadsheetDc)->getData(__DIR__.'/stubs/excel1.xlsx'))
        ->toEqual(require __DIR__.'/stubs/excel1.php');
});
