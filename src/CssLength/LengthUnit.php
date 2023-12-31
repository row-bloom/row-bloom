<?php

namespace RowBloom\CssLength;

enum LengthUnit: string
{
    case PIXEL = 'px';
    case CENTIMETER = 'cm';
    case MILLIMETER = 'mm';
    case INCH = 'in';
    case POINT = 'pt';
    case PICA = 'pc';
    // TODO: Q (impact RowBloom\CssLength\Length::__call)

    /**
     * @var (int|float)[][] Absolute units only
     *
     * @see https://www.w3.org/TR/css-values-3/#absolute-lengths
     */
    protected const ABSOLUTE_UNITS_EQUIVALENCE = [
        'in' => [
            'cm' => 2.54,
            'mm' => 25.4,
            'px' => 96,
            'pt' => 72,
            'pc' => 6,
        ],
        'cm' => [
            'in' => 1 / 2.54,
            'mm' => 10,
            'px' => 96 / 2.54,
            'pt' => 72 / 2.54,
            'pc' => 6 / 2.54,
        ],
        'mm' => [
            'in' => 1 / 25.4,
            'cm' => 1 / 10,
            'px' => 96 / 25.4,
            'pt' => 72 / 25.4,
            'pc' => 6 / 25.4,
        ],
        'px' => [
            'in' => 1 / 96,
            'cm' => 2.54 / 96,
            'mm' => 25.4 / 96,
            'pt' => 72 / 96,
            'pc' => 6 / 96,
        ],
        'pt' => [
            'in' => 1 / 72,
            'cm' => 2.54 / 72,
            'mm' => 25.4 / 72,
            'px' => 96 / 72,
            'pc' => 12 / 72,
        ],
        'pc' => [
            'in' => 1 / 6,
            'cm' => 2.54 / 6,
            'mm' => 25.4 / 6,
            'px' => 96 / 6,
            'pt' => 72 / 6,
        ],
    ];

    public static function absoluteUnitsEquivalence(self|string $from, self|string $to): float
    {
        $from = $from instanceof self ? $from->value : $from;
        $to = $to instanceof self ? $to->value : $to;

        return self::ABSOLUTE_UNITS_EQUIVALENCE[$from][$to];
    }

    /** @return string[] */
    public static function absoluteUnits(): array
    {
        return array_keys(self::ABSOLUTE_UNITS_EQUIVALENCE);
    }
}
