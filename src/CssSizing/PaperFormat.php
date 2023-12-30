<?php

namespace RowBloom\CssSizing;

enum PaperFormat: string
{
    case _4A0 = '4A0';
    case _2A0 = '2A0';
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
    case _RA0 = 'RA0';
    case _RA1 = 'RA1';
    case _RA2 = 'RA2';
    case _RA3 = 'RA3';
    case _RA4 = 'RA4';
    case _SRA0 = 'SRA0';
    case _SRA1 = 'SRA1';
    case _SRA2 = 'SRA2';
    case _SRA3 = 'SRA3';
    case _SRA4 = 'SRA4';
    case _LETTER = 'LETTER';
    case _LEGAL = 'LEGAL';
    case _LEDGER = 'LEDGER';
    case _TABLOID = 'TABLOID';
    case _EXECUTIVE = 'EXECUTIVE';
    case _FOLIO = 'FOLIO';
    case _B = 'B';
    case _ADEM = 'ADEM';
    case _Y = 'Y';
    case _ROYAL = 'ROYAL';
    // case _JIS_B5 = 'JIS-B5';
    // case _JIS_B4 = 'JIS-B4';

    public function size(LengthUnit $to = LengthUnit::MILLIMETER): BoxSize
    {
        if ($to === LengthUnit::MILLIMETER) {
            return $this->mmSize();
        } elseif ($to === LengthUnit::INCH) {
            return $this->inSize();
        }

        $mmSize = $this->inSize();

        return new BoxSize($mmSize->width->convert($to), $mmSize->height->convert($to));
    }

    // ? pxSizes ...

    public function mmSize(): BoxSize
    {
        $size = match ($this) {
            self::_4A0 => [1682, 2378],
            self::_2A0 => [1189, 1682],
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
            self::_RA0 => [860, 1220],
            self::_RA1 => [610, 860],
            self::_RA2 => [430, 610],
            self::_RA3 => [305, 430],
            self::_RA4 => [215, 305],
            self::_SRA0 => [900, 1280],
            self::_SRA1 => [640, 900],
            self::_SRA2 => [450, 640],
            self::_SRA3 => [320, 450],
            self::_SRA4 => [225, 320],
            self::_LETTER => [216, 279],
            self::_LEGAL => [216, 356],
            self::_LEDGER => [279, 432],
            self::_TABLOID => [432, 279],
            self::_EXECUTIVE => [184, 267],
            self::_FOLIO => [210, 330],
            self::_B => [500, 707],
            self::_ADEM => [216, 356],
            self::_Y => [330, 430],
            self::_ROYAL => [432, 559],
            // self::_JIS_B4 => [257, 364],
            // self::_JIS_B5 => [182, 257],
        };

        return new BoxSize(
            Length::fromNumberUnit($size[0], LengthUnit::MILLIMETER),
            Length::fromNumberUnit($size[1], LengthUnit::MILLIMETER),
        );
    }

    public function inSize(): BoxSize
    {
        $size = match ($this) {
            self::_4A0 => [66.14, 93.70],
            self::_2A0 => [46.81, 66.14],
            self::_A0 => [33.11, 46.81],
            self::_A1 => [23.39, 33.11],
            self::_A2 => [16.54, 23.39],
            self::_A3 => [11.69, 16.54],
            self::_A4 => [8.27, 11.69],
            self::_A5 => [5.83, 8.27],
            self::_A6 => [4.13, 5.83],
            self::_A7 => [2.91, 4.13],
            self::_A8 => [2.05, 2.91],
            self::_A9 => [1.46, 2.05],
            self::_A10 => [1.02, 1.46],
            self::_B0 => [39.37, 55.67],
            self::_B1 => [27.83, 39.37],
            self::_B2 => [19.69, 27.83],
            self::_B3 => [13.90, 19.69],
            self::_B4 => [9.84, 13.90],
            self::_B5 => [6.93, 9.84],
            self::_B6 => [4.92, 6.93],
            self::_B7 => [3.46, 4.92],
            self::_B8 => [2.44, 3.46],
            self::_B9 => [1.73, 2.44],
            self::_B10 => [1.22, 1.73],
            self::_C0 => [36.10, 51.06],
            self::_C1 => [25.51, 36.10],
            self::_C2 => [18.03, 25.51],
            self::_C3 => [12.76, 18.03],
            self::_C4 => [9.02, 12.76],
            self::_C5 => [6.38, 9.02],
            self::_C6 => [4.49, 6.38],
            self::_C7 => [3.19, 4.49],
            self::_C8 => [2.24, 3.19],
            self::_C9 => [1.57, 2.24],
            self::_C10 => [1.10, 1.57],
            self::_RA0 => [33.86, 48.03],
            self::_RA1 => [24.02, 33.86],
            self::_RA2 => [16.93, 24.02],
            self::_RA3 => [12.01, 16.93],
            self::_RA4 => [8.46, 12.01],
            self::_SRA0 => [35.43, 49.21],
            self::_SRA1 => [25.20, 35.43],
            self::_SRA2 => [17.72, 25.20],
            self::_SRA3 => [12.60, 17.72],
            self::_SRA4 => [8.86, 12.60],
            self::_LETTER => [8.50, 11.00],
            self::_LEGAL => [8.50, 14.00],
            self::_LEDGER => [11.00, 17.00],
            self::_TABLOID => [17.00, 11.00],
            self::_EXECUTIVE => [7.25, 10.50],
            self::_FOLIO => [8.27, 13.00],
            self::_B => [19.69, 27.83],
            self::_ADEM => [8.50, 14.00],
            self::_Y => [13.00, 16.93],
            self::_ROYAL => [17.00, 22.01]
        };

        return new BoxSize(
            Length::fromNumberUnit($size[0], LengthUnit::INCH),
            Length::fromNumberUnit($size[1], LengthUnit::INCH),
        );
    }
}
