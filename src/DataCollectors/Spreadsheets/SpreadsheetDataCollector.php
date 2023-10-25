<?php

namespace RowBloom\RowBloom\DataCollectors\Spreadsheets;

use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Gnumeric;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\Reader\Slk;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xml;
use RowBloom\RowBloom\DataCollectorContract;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Types\Table;

class SpreadsheetDataCollector implements DataCollectorContract
{
    public const NAME = 'Spreadsheet';

    public function getTable(File|string $file): Table
    {
        $file = $file instanceof File ? $file : app()->make(File::class, ['path' => $file]);

        $file->mustExist()->mustBeReadable()->mustBeFile();

        $reader = $this->getReaderTypeFromExtension($file);

        $spreadsheet = $reader->load($file);

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

    public static function getSupportedFileExtensions(): array
    {
        return [
            'xlsx' => true,
            'xlsm' => true,
            'xltx' => true,
            'xltm' => true,
            'xls' => true,
            'xlt' => true,
            'ods' => true,
            'ots' => true,
            'slk' => true,
            'xml' => true,
            'gnumeric' => true,
            'htm' => true,
            'html' => true,
            'csv' => true,
        ];
    }

    /**
     * Copied from phpoffice/phpspreadsheet:
     * - Version: 1.28.0
     * - File: vendor\phpoffice\phpspreadsheet\src\PhpSpreadsheet\IOFactory.php
     *
     * setTestAutoDetect() is unique to CSV reader
     */
    private function getReaderTypeFromExtension(File $file): IReader
    {
        $class = match ($file->extension()) {
            'xlsx', 'xlsm', 'xltx', 'xltm' => Xlsx::class,
            'xls', 'xlt' => Xls::class,
            'ods', 'ots' => Ods::class,
            'slk' => Slk::class,
            'xml' => Xml::class,
            'gnumeric' => Gnumeric::class,
            'htm', 'html' => Html::class,
            'csv' => Csv::class,
            default => throw new SpreadsheetException("Unable to identify a Spreadsheet reader for {$file}"),
        };

        $reader = app()->make($class);

        if ($reader instanceof Csv) {
            $reader->setTestAutoDetect(false);
        }

        return $reader;
    }
}
