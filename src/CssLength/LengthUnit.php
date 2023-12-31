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

    public static function absoluteUnitsEquivalence(self|string $from, self|string $to, float|int $value = 1, ?int $decimals = null): float
    {
        /** @see https://www.w3.org/TR/css-values-3/#absolute-lengths */
        $absoluteUnitsEquivalence = [
            'in' => [
                'cm' => fn (float|int $v) => $v * 2.54,
                'mm' => fn (float|int $v) => $v * 25.4,
                'px' => fn (float|int $v) => $v * 96,
                'pt' => fn (float|int $v) => $v * 72,
                'pc' => fn (float|int $v) => $v * 6,
            ],
            'cm' => [
                'in' => fn (float|int $v) => $v * 1 / 2.54,
                'mm' => fn (float|int $v) => $v * 10,
                'px' => fn (float|int $v) => $v * 96 / 2.54,
                'pt' => fn (float|int $v) => $v * 72 / 2.54,
                'pc' => fn (float|int $v) => $v * 6 / 2.54,
            ],
            'mm' => [
                'in' => fn (float|int $v) => $v * 1 / 25.4,
                'cm' => fn (float|int $v) => $v * 1 / 10,
                'px' => fn (float|int $v) => $v * 96 / 25.4,
                'pt' => fn (float|int $v) => $v * 72 / 25.4,
                'pc' => fn (float|int $v) => $v * 6 / 25.4,
            ],
            'px' => [
                'in' => fn (float|int $v) => $v * 1 / 96,
                'cm' => fn (float|int $v) => $v * 2.54 / 96,
                'mm' => fn (float|int $v) => $v * 25.4 / 96,
                'pt' => fn (float|int $v) => $v * 72 / 96,
                'pc' => fn (float|int $v) => $v * 6 / 96,
            ],
            'pt' => [
                'in' => fn (float|int $v) => $v * 1 / 72,
                'cm' => fn (float|int $v) => $v * 2.54 / 72,
                'mm' => fn (float|int $v) => $v * 25.4 / 72,
                'px' => fn (float|int $v) => $v * 96 / 72,
                'pc' => fn (float|int $v) => $v * 12 / 72,
            ],
            'pc' => [
                'in' => fn (float|int $v) => $v * 1 / 6,
                'cm' => fn (float|int $v) => $v * 2.54 / 6,
                'mm' => fn (float|int $v) => $v * 25.4 / 6,
                'px' => fn (float|int $v) => $v * 96 / 6,
                'pt' => fn (float|int $v) => $v * 72 / 6,
            ],
        ];

        $from = $from instanceof self ? $from->value : $from;
        $to = $to instanceof self ? $to->value : $to;

        if ($from === $to) {
            return $value;
        }

        $result = $absoluteUnitsEquivalence[$from][$to]($value);

        if (! is_null($decimals)) {
            $n = 10 ^ $decimals;

            return ((int) ($result * $n)) / $n;
        }

        return $result;
    }

    /** @return string[] */
    public static function absoluteUnits(): array
    {
        return ['in', 'mm', 'cm', 'px', 'pt', 'pc', 'Q'];
    }
}
