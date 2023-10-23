<?php

namespace RowBloom\RowBloom\DataCollectors;

use RowBloom\RowBloom\DataCollectors\Folder\FolderDataCollector;
use RowBloom\RowBloom\DataCollectors\Json\JsonDataCollector;
use RowBloom\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;

enum DataCollector: string
{
    case Spreadsheet = SpreadsheetDataCollector::class;
    case Json = JsonDataCollector::class;
    case Folder = FolderDataCollector::class;
}
