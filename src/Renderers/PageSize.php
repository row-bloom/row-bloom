<?php

namespace ElaborateCode\RowBloom\Renderers;

use Exception;

// ? turn to data object
class PageSize
{
    private static $instance;

    /**
     * in mm
     * TODO: use constants
     */
    private array $sizes = [
        '4A0' => [1682, 2378],
        '2A0' => [1189, 1682],
        'A0' => [841, 1189],
        'A1' => [594, 841],
        'A2' => [420, 594],
        'A3' => [297, 420],
        'A4' => [210, 297],
        'A5' => [148, 210],
        'A6' => [105, 148],
        'A7' => [74, 105],
        'A8' => [52, 74],
        'A9' => [37, 52],
        'A10' => [26, 37],
        'B0' => [1000, 1414],
        'B1' => [707, 1000],
        'B2' => [500, 707],
        'B3' => [353, 500],
        'B4' => [250, 353],
        'B5' => [176, 250],
        'B6' => [125, 176],
        'B7' => [88, 125],
        'B8' => [62, 88],
        'B9' => [44, 62],
        'B10' => [31, 44],
        'C0' => [917, 1297],
        'C1' => [648, 917],
        'C2' => [458, 648],
        'C3' => [324, 458],
        'C4' => [229, 324],
        'C5' => [162, 229],
        'C6' => [114, 162],
        'C7' => [81, 114],
        'C8' => [57, 81],
        'C9' => [40, 57],
        'C10' => [28, 40],
        'RA0' => [860, 1220],
        'RA1' => [610, 860],
        'RA2' => [430, 610],
        'RA3' => [305, 430],
        'RA4' => [215, 305],
        'SRA0' => [900, 1280],
        'SRA1' => [640, 900],
        'SRA2' => [450, 640],
        'SRA3' => [320, 450],
        'SRA4' => [225, 320],
        'LETTER' => [216, 279],
        'LEGAL' => [216, 356],
        'LEDGER' => [279, 432],
        'TABLOID' => [432, 279],
        'EXECUTIVE' => [184, 267],
        'FOLIO' => [210, 330],
        'B' => [500, 707],
        'ADEM' => [216, 356],
        'Y' => [330, 430],
        'ROYAL' => [432, 559],
    ];

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getIn(string $pageFormat, string $to = UnitManager::MILLIMETER_UNIT): array
    {
        $instance = self::getInstance();

        $to = strtolower(trim($to));

        $pageSize = $instance->sizes[$pageFormat] ?? null;

        if (is_null($pageSize)) {
            throw new Exception("Invalid page format {$pageFormat}");
        }

        return [
            UnitManager::convertAbs(UnitManager::MILLIMETER_UNIT, $to, $pageSize[0]),
            UnitManager::convertAbs(UnitManager::MILLIMETER_UNIT, $to, $pageSize[1]),
        ];
    }
}
