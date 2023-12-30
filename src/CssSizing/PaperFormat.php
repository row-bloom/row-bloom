<?php

/**
 * @see https://www.papersizes.org/
 * @see https://www.iso.org/standard/4178.html ISO 269:1985
 * @see https://www.iso.org/standard/36631.html ISO 216:2007
 * @ee https://webstore.ansi.org/standards/asme/asmey142020 ASME Y14.1-2020
 */

namespace RowBloom\CssSizing;

enum PaperFormat: string
{
    // ISO 216:2007
    case _A0 = 'A0';
    case _A1 = 'A1';
    case _A2 = 'A2';
    case _A3 = 'A3';
    case _A4 = 'A4';
    case _A5 = 'A5';
    case _A6 = 'A6';
    case _A7 = 'A7';
    case _A8 = 'A8';
    case _A9 = 'A9';
    case _A10 = 'A10';
    case _B0 = 'B0';
    case _B1 = 'B1';
    case _B2 = 'B2';
    case _B3 = 'B3';
    case _B4 = 'B4';
    case _B5 = 'B5';
    case _B6 = 'B6';
    case _B7 = 'B7';
    case _B8 = 'B8';
    case _B9 = 'B9';
    case _B10 = 'B10';
    // ISO 269:1985
    case _C0 = 'C0';
    case _C1 = 'C1';
    case _C2 = 'C2';
    case _C3 = 'C3';
    case _C4 = 'C4';
    case _C5 = 'C5';
    case _C6 = 'C6';
    case _C7 = 'C7';
    case _C8 = 'C8';
    case _C9 = 'C9';
    case _C10 = 'C10';
    // ASME Y14.1-2020 US Paper Sizes
    case _LETTER = 'LETTER';
    case _LEGAL = 'LEGAL';
    case _LEDGER = 'LEDGER';
    case _TABLOID = 'TABLOID';

    public function size(LengthUnit $to = LengthUnit::MILLIMETER): BoxSize
    {
        if ($to === LengthUnit::MILLIMETER) {
            return $this->mmSize();
        }

        $mmSize = $this->mmSize();

        return new BoxSize($mmSize->width->convert($to), $mmSize->height->convert($to));
    }

    public function mmSize(): BoxSize
    {
        $size = match ($this) {
            self::_A0 => [841, 1189],
            self::_A1 => [594, 841],
            self::_A2 => [420, 594],
            self::_A3 => [297, 420],
            self::_A4 => [210, 297],
            self::_A5 => [148, 210],
            self::_A6 => [105, 148],
            self::_A7 => [74, 105],
            self::_A8 => [52, 74],
            self::_A9 => [37, 52],
            self::_A10 => [26, 37],
            self::_B0 => [1000, 1414],
            self::_B1 => [707, 1000],
            self::_B2 => [500, 707],
            self::_B3 => [353, 500],
            self::_B4 => [250, 353],
            self::_B5 => [176, 250],
            self::_B6 => [125, 176],
            self::_B7 => [88, 125],
            self::_B8 => [62, 88],
            self::_B9 => [44, 62],
            self::_B10 => [31, 44],
            self::_C0 => [917, 1297],
            self::_C1 => [648, 917],
            self::_C2 => [458, 648],
            self::_C3 => [324, 458],
            self::_C4 => [229, 324],
            self::_C5 => [162, 229],
            self::_C6 => [114, 162],
            self::_C7 => [81, 114],
            self::_C8 => [57, 81],
            self::_C9 => [40, 57],
            self::_C10 => [28, 40],
            self::_LETTER => [216, 279],
            self::_LEGAL => [216, 356],
            self::_LEDGER => [279, 432],
            self::_TABLOID => [432, 279],
        };

        return new BoxSize(
            Length::fromNumberUnit($size[0], LengthUnit::MILLIMETER),
            Length::fromNumberUnit($size[1], LengthUnit::MILLIMETER),
        );
    }
}
