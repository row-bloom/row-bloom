<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectors\Folder\FolderDataCollector;
use ElaborateCode\RowBloom\DataCollectors\Json\JsonDataCollector;
use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

enum DataCollector: string
{
    case Spreadsheet = SpreadsheetDataCollector::class;
    case Json = JsonDataCollector::class;
    case Folder = FolderDataCollector::class;
}
