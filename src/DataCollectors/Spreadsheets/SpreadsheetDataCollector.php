<?php

namespace ElaborateCode\RowBloom\DataCollectors\Spreadsheets;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\Types\Table;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetDataCollector implements DataCollectorContract
{
    public function getTable(string $path): Table
    {
        // TODO: wrap path and validate

        // TODO Support composition behavior for folders

        $spreadsheet = IOFactory::load($path);

        // TODO access all sheets

        $data = $spreadsheet->getActiveSheet()->toArray();

        $labels = array_shift($data);

        if (count($labels) > count(array_flip($labels))) {
            throw new SpreadsheetException('Duplicate labels '.implode(', ', $labels));
        }
        // ? check empty label

        $data = array_map(
            fn (array $row) => array_combine($labels, $row),
            $data
        );

        return Table::fromArray($data);
    }
}

// READER_XLSX
// READER_XLS
// READER_XML
// READER_ODS
// READER_SLK
// READER_GNUMERIC
// READER_HTML
// READER_CSV
