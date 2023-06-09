<?php

namespace ElaborateCode\RowBloom\DataCollectors\Spreadsheets;

use ElaborateCode\RowBloom\DcContract;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetDc implements DcContract
{
    public function getData(string $path): array
    {
        $spreadsheet = IOFactory::load($path);

        // TODO access all sheets

        $data = $spreadsheet->getActiveSheet()->toArray();

        $headers = array_shift($data);

        // ! headers must not contain empty values

        $data = array_map(
            fn (array $row) => array_combine($headers, $row),
            $data
        );

        return $data;
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
