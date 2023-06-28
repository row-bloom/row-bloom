<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

use Exception;

enum PaperFormat: string
{
    case FORMAT_4A0 = '4A0';
    case FORMAT_2A0 = '2A0';
    case FORMAT_A0 = 'A0';
    case FORMAT_A1 = 'A1';
    case FORMAT_A2 = 'A2';
    case FORMAT_A3 = 'A3';
    case FORMAT_A4 = 'A4';
    case FORMAT_A5 = 'A5';
    case FORMAT_A6 = 'A6';
    case FORMAT_A7 = 'A7';
    case FORMAT_A8 = 'A8';
    case FORMAT_A9 = 'A9';
    case FORMAT_A10 = 'A10';
    case FORMAT_B0 = 'B0';
    case FORMAT_B1 = 'B1';
    case FORMAT_B2 = 'B2';
    case FORMAT_B3 = 'B3';
    case FORMAT_B4 = 'B4';
    case FORMAT_B5 = 'B5';
    case FORMAT_B6 = 'B6';
    case FORMAT_B7 = 'B7';
    case FORMAT_B8 = 'B8';
    case FORMAT_B9 = 'B9';
    case FORMAT_B10 = 'B10';
    case FORMAT_C0 = 'C0';
    case FORMAT_C1 = 'C1';
    case FORMAT_C2 = 'C2';
    case FORMAT_C3 = 'C3';
    case FORMAT_C4 = 'C4';
    case FORMAT_C5 = 'C5';
    case FORMAT_C6 = 'C6';
    case FORMAT_C7 = 'C7';
    case FORMAT_C8 = 'C8';
    case FORMAT_C9 = 'C9';
    case FORMAT_C10 = 'C10';
    case FORMAT_RA0 = 'RA0';
    case FORMAT_RA1 = 'RA1';
    case FORMAT_RA2 = 'RA2';
    case FORMAT_RA3 = 'RA3';
    case FORMAT_RA4 = 'RA4';
    case FORMAT_SRA0 = 'SRA0';
    case FORMAT_SRA1 = 'SRA1';
    case FORMAT_SRA2 = 'SRA2';
    case FORMAT_SRA3 = 'SRA3';
    case FORMAT_SRA4 = 'SRA4';
    case FORMAT_LETTER = 'LETTER';
    case FORMAT_LEGAL = 'LEGAL';
    case FORMAT_LEDGER = 'LEDGER';
    case FORMAT_TABLOID = 'TABLOID';
    case FORMAT_EXECUTIVE = 'EXECUTIVE';
    case FORMAT_FOLIO = 'FOLIO';
    case FORMAT_B = 'B';
    case FORMAT_ADEM = 'ADEM';
    case FORMAT_Y = 'Y';
    case FORMAT_ROYAL = 'ROYAL';

    /**
     * Sizes in mm
     */
    public function size(string $to = UnitManager::MILLIMETER_UNIT): array
    {
        $to = strtolower(trim($to));

        $size = match ($this) {
            self::FORMAT_4A0 => [1682, 2378],
            self::FORMAT_2A0 => [1189, 1682],
            self::FORMAT_A0 => [841, 1189],
            self::FORMAT_A1 => [594, 841],
            self::FORMAT_A2 => [420, 594],
            self::FORMAT_A3 => [297, 420],
            self::FORMAT_A4 => [210, 297],
            self::FORMAT_A5 => [148, 210],
            self::FORMAT_A6 => [105, 148],
            self::FORMAT_A7 => [74, 105],
            self::FORMAT_A8 => [52, 74],
            self::FORMAT_A9 => [37, 52],
            self::FORMAT_A10 => [26, 37],
            self::FORMAT_B0 => [1000, 1414],
            self::FORMAT_B1 => [707, 1000],
            self::FORMAT_B2 => [500, 707],
            self::FORMAT_B3 => [353, 500],
            self::FORMAT_B4 => [250, 353],
            self::FORMAT_B5 => [176, 250],
            self::FORMAT_B6 => [125, 176],
            self::FORMAT_B7 => [88, 125],
            self::FORMAT_B8 => [62, 88],
            self::FORMAT_B9 => [44, 62],
            self::FORMAT_B10 => [31, 44],
            self::FORMAT_C0 => [917, 1297],
            self::FORMAT_C1 => [648, 917],
            self::FORMAT_C2 => [458, 648],
            self::FORMAT_C3 => [324, 458],
            self::FORMAT_C4 => [229, 324],
            self::FORMAT_C5 => [162, 229],
            self::FORMAT_C6 => [114, 162],
            self::FORMAT_C7 => [81, 114],
            self::FORMAT_C8 => [57, 81],
            self::FORMAT_C9 => [40, 57],
            self::FORMAT_C10 => [28, 40],
            self::FORMAT_RA0 => [860, 1220],
            self::FORMAT_RA1 => [610, 860],
            self::FORMAT_RA2 => [430, 610],
            self::FORMAT_RA3 => [305, 430],
            self::FORMAT_RA4 => [215, 305],
            self::FORMAT_SRA0 => [900, 1280],
            self::FORMAT_SRA1 => [640, 900],
            self::FORMAT_SRA2 => [450, 640],
            self::FORMAT_SRA3 => [320, 450],
            self::FORMAT_SRA4 => [225, 320],
            self::FORMAT_LETTER => [216, 279],
            self::FORMAT_LEGAL => [216, 356],
            self::FORMAT_LEDGER => [279, 432],
            self::FORMAT_TABLOID => [432, 279],
            self::FORMAT_EXECUTIVE => [184, 267],
            self::FORMAT_FOLIO => [210, 330],
            self::FORMAT_B => [500, 707],
            self::FORMAT_ADEM => [216, 356],
            self::FORMAT_Y => [330, 430],
            self::FORMAT_ROYAL => [432, 559],
            default => throw new Exception('Invalid page format'),
        };

        return [
            UnitManager::convertAbs(UnitManager::MILLIMETER_UNIT, $to, $size[0]),
            UnitManager::convertAbs(UnitManager::MILLIMETER_UNIT, $to, $size[1]),
        ];
    }
}
