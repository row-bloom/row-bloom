<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

use Exception;

// ? turn to data object
class Paper
{
    public const FORMAT_4A0 = [1682, 2378];

    public const FORMAT_2A0 = [1189, 1682];

    public const FORMAT_A0 = [841, 1189];

    public const FORMAT_A1 = [594, 841];

    public const FORMAT_A2 = [420, 594];

    public const FORMAT_A3 = [297, 420];

    public const FORMAT_A4 = [210, 297];

    public const FORMAT_A5 = [148, 210];

    public const FORMAT_A6 = [105, 148];

    public const FORMAT_A7 = [74, 105];

    public const FORMAT_A8 = [52, 74];

    public const FORMAT_A9 = [37, 52];

    public const FORMAT_A10 = [26, 37];

    public const FORMAT_B0 = [1000, 1414];

    public const FORMAT_B1 = [707, 1000];

    public const FORMAT_B2 = [500, 707];

    public const FORMAT_B3 = [353, 500];

    public const FORMAT_B4 = [250, 353];

    public const FORMAT_B5 = [176, 250];

    public const FORMAT_B6 = [125, 176];

    public const FORMAT_B7 = [88, 125];

    public const FORMAT_B8 = [62, 88];

    public const FORMAT_B9 = [44, 62];

    public const FORMAT_B10 = [31, 44];

    public const FORMAT_C0 = [917, 1297];

    public const FORMAT_C1 = [648, 917];

    public const FORMAT_C2 = [458, 648];

    public const FORMAT_C3 = [324, 458];

    public const FORMAT_C4 = [229, 324];

    public const FORMAT_C5 = [162, 229];

    public const FORMAT_C6 = [114, 162];

    public const FORMAT_C7 = [81, 114];

    public const FORMAT_C8 = [57, 81];

    public const FORMAT_C9 = [40, 57];

    public const FORMAT_C10 = [28, 40];

    public const FORMAT_RA0 = [860, 1220];

    public const FORMAT_RA1 = [610, 860];

    public const FORMAT_RA2 = [430, 610];

    public const FORMAT_RA3 = [305, 430];

    public const FORMAT_RA4 = [215, 305];

    public const FORMAT_SRA0 = [900, 1280];

    public const FORMAT_SRA1 = [640, 900];

    public const FORMAT_SRA2 = [450, 640];

    public const FORMAT_SRA3 = [320, 450];

    public const FORMAT_SRA4 = [225, 320];

    public const FORMAT_LETTER = [216, 279];

    public const FORMAT_LEGAL = [216, 356];

    public const FORMAT_LEDGER = [279, 432];

    public const FORMAT_TABLOID = [432, 279];

    public const FORMAT_EXECUTIVE = [184, 267];

    public const FORMAT_FOLIO = [210, 330];

    public const FORMAT_B = [500, 707];

    public const FORMAT_ADEM = [216, 356];

    public const FORMAT_Y = [330, 430];

    public const FORMAT_ROYAL = [432, 559];

    public static function getIn(string $format, string $to = UnitManager::MILLIMETER_UNIT): array
    {
        $to = strtolower(trim($to));

        $constantName = 'self::FORMAT_'.$format;

        if (defined($constantName)) {
            $size = constant($constantName);
        } else {
            throw new Exception("Invalid page format {$format}");
        }

        return [
            UnitManager::convertAbs(UnitManager::MILLIMETER_UNIT, $to, $size[0]),
            UnitManager::convertAbs(UnitManager::MILLIMETER_UNIT, $to, $size[1]),
        ];
    }
}
