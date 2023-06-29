<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

enum DataCollector: string
{
    case Spreadsheet = SpreadsheetDataCollector::class;
}
