<?php

use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

it('parses', function () {
    $dataCollector = DataCollectorFactory::make('spreadsheet');

    expect($dataCollector)->toBeInstanceOf(SpreadsheetDataCollector::class);

    expect(
        $dataCollector->getTable(__DIR__.'/../../../stubs/1csv.csv')->toArray()
    )->toEqual([
        ['a' => '2', 'b' => '2'],
        ['a' => '3', 'b' => '3'],
    ]);
});
