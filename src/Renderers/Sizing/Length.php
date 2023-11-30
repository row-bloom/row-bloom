<?php

/**
 * @see https://www.w3.org/TR/css-values-3
 * @see https://www.w3.org/TR/css-sizing-3
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/length
 *
 * @see https://www.w3.org/TR/?filter-tr-name=&status%5B%5D=candidateStandard&status%5B%5D=standard&tags%5B%5D=css
 * @see https://www.w3.org/TR/css-syntax-3
 *
 * TODO: support functional notation. calc(50% - 2em)
 */

namespace RowBloom\RowBloom\Renderers\Sizing;

use RowBloom\RowBloom\RowBloomException;

class Length
{
    /**
     * @var (int|float)[][] Absolute units only
     *
     * ? enum keys
     */
    protected const ABSOLUTE_UNIT_EQUIVALENCE = [
        'px' => [
            'cm' => 0.02646,
            'mm' => 0.2646,
            'in' => 0.0104,
            'pt' => 0.75,
            'pc' => 0.0625,
        ],
        'cm' => [
            'px' => 37.7953,
            'mm' => 10,
            'in' => 0.3937,
            'pt' => 28.3465,
            'pc' => 2.3622,
        ],
        'mm' => [
            'px' => 3.7795,
            'cm' => 0.1,
            'in' => 0.0394,
            'pt' => 2.8346,
            'pc' => 0.2362,
        ],
        'in' => [
            'px' => 96,
            'cm' => 2.54,
            'mm' => 25.4,
            'pt' => 72,
            'pc' => 6,
        ],
        'pt' => [
            'px' => 1.3333,
            'cm' => 0.03528,
            'mm' => 0.3528,
            'in' => 0.0138,
            'pc' => 0.0833,
        ],
        'pc' => [
            'px' => 16,
            'cm' => 0.4233,
            'mm' => 4.2333,
            'in' => 0.1667,
            'pt' => 12,
        ],
    ];

    public static function fromValue(string $value, LengthUnit $readUnit): static
    {
        if (is_numeric($value)) {
            return Length::fromNumber((float)$value, $readUnit);
        }

        return Length::fromString($value, $readUnit);
    }

    public static function fromNumber(float|int $value, LengthUnit $readUnit, LengthUnit $sourceUnit = null): static
    {
        if (! is_numeric($value)) {
            throw new RowBloomException("Not numeric value '{$value}'");
        }

        $sourceUnit ??= $readUnit;

        return new static($value, $readUnit, $sourceUnit);
    }

    public static function fromString(string $value, LengthUnit $readUnit): static
    {
        $value = trim((string) $value);

        if (is_numeric($value)) {
            return Length::fromNumber((float)$value, $readUnit);
        }

        $parsed = static::parseLengthDimension($value);

        return Length::fromNumber($parsed['value'], LengthUnit::from($parsed['unit']))
            ->setUnit($readUnit);
    }

    final protected function __construct(
        private readonly float|int $value,
        private LengthUnit $readUnit,
        private readonly LengthUnit $sourceUnit
    ) {
        // TODO: if relative unit require a reference
    }

    /**
     * @return array{value: float, unit: string}
     *
     * @see https://www.w3.org/TR/css-values-3/#lengths
     */
    protected static function parseLengthDimension(string $value): array
    {
        $units = implode('|', array_keys(static::ABSOLUTE_UNIT_EQUIVALENCE));
        $regex = "/^(?<value>\d+(\.\d+)?)(?<unit>{$units})$/";

        preg_match($regex, $value, $parsed) ?:
            throw new RowBloomException('Invalid CSS dimension: '.$value);

        /** @phpstan-ignore-next-line */
        return $parsed;
    }

    public function setUnit(LengthUnit $readUnit): static
    {
        $this->readUnit = $readUnit;

        return $this;
    }

    public function convert(LengthUnit $readUnit): static
    {
        return (clone $this)->setUnit($readUnit);
    }

    public function value(): float
    {
        return $this->valueIn($this->readUnit);
    }

    public function valueIn(LengthUnit $readUnit): float
    {
        if ($readUnit === $this->sourceUnit) {
            return $this->value;
        }

        return $this->value * static::ABSOLUTE_UNIT_EQUIVALENCE[$this->sourceUnit->value][$readUnit->value];
    }
}
