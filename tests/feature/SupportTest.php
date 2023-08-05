<?php

use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use ElaborateCode\RowBloom\Support;

it('lists capabilities', function() {
    /** @var Support */
    $support = app()->get(Support::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json', 'csv', 'xlsx']);

    expect($support->getDataCollectorDrivers())
        ->toHaveKeys(['Spreadsheet', 'Folder', 'Json'])
        ->toContain(SpreadsheetDataCollector::class);
});
