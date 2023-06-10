<?php

use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

it('makes', function () {
    expect((new DataCollectorFactory)->make())->toBeInstanceOf(SpreadsheetDataCollector::class);
});
