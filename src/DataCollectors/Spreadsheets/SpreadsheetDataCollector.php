<?php

namespace ElaborateCode\RowBloom\DataCollectors\Spreadsheets;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Table;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetDataCollector implements DataCollectorContract
{
    public function getTable(File|string $file): Table
    {
        $file = $file instanceof File ? $file : ROW_BLOOM_CONTAINER->make(File::class, ['path' => $file]);

        // XLSX, XLS, XML, ODS, SLK, GNUMERIC, HTML, CSV
        $file->mustExist()->mustBeReadable()->mustBeFile();

        $spreadsheet = IOFactory::load($file);

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
