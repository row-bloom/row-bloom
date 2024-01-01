<?php

namespace RowBloom\SpreadsheetDataLoader;

use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Gnumeric;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\Reader\Slk;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xml;
use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\DataLoaders\FsContract as DataLoadersFsContract;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

class SpreadsheetDataLoader implements DataLoadersFsContract
{
    public const NAME = 'Spreadsheet';

    public function __construct(protected ?Config $config = null)
    {
    }

    public function getTable(TableLocation $tableLocation, ?Config $config = null): Table
    {
        $file = $tableLocation->toFile();
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
            'xlsx' => 100,
            'xlsm' => 100,
            'xltx' => 100,
            'xltm' => 100,
            'xls' => 100,
            'xlt' => 100,
            'ods' => 100,
            'ots' => 100,
            'slk' => 100,
            'xml' => 100,
            'gnumeric' => 100,
            'htm' => 100,
            'html' => 100,
            'csv' => 100,
        ];
    }

    public static function getFolderSupport(): ?int
    {
        return null;
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

        $reader = new $class;

        if ($reader instanceof Csv) {
            $reader->setTestAutoDetect(false);
        }

        return $reader;
    }
}
