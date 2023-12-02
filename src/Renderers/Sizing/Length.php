<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/length
 * @see https://www.w3.org/TR/css-sizing-3
 * @see https://www.w3.org/TR/css-syntax-3
 */

namespace RowBloom\RowBloom\Renderers\Sizing;

use RowBloom\RowBloom\RowBloomException;
use Stringable;

class Length implements Stringable
{
    /**
     * @var (int|float)[][] Absolute units only
     *
     * @see https://www.w3.org/TR/css-values-3/#absolute-lengths
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

    public static function fromString(string $value): static
    {
        $value = trim((string) $value);

        $parsed = static::parseLengthDimension($value);

        return Length::fromNumberUnit($parsed['value'], LengthUnit::from($parsed['unit']));
    }

    public static function fromNumberUnit(float|int $value, LengthUnit $unit): static
    {
        if (! is_numeric($value)) {
            throw new RowBloomException("Not numeric value '{$value}'");
        }

        return new static($value, $unit);
    }

    final protected function __construct(
        public readonly float|int $value,
        public readonly LengthUnit $unit,
        public ?LengthUnit $readUnit = null,
    ) {
        $this->readUnit ??= $this->unit;
    }

    // TODO: setReference
    // TODO: support functional notation. calc(50% - 2em)

    /**
     * @return array{value: float, unit: string}
     *
     * @see https://www.w3.org/TR/css-values-3/#lengths
     */
    protected static function parseLengthDimension(string $value): array
    {
        $value = trim($value);

        if($value === '0') {
            return ['value' => 0, 'unit' => 'px'];
        }

        $units = implode('|', array_keys(static::ABSOLUTE_UNIT_EQUIVALENCE));
        $regex = "/^(?<value>\d+(\.\d+)?)(?<unit>{$units})$/";

        preg_match($regex, $value, $parsed) ?:
            throw new RowBloomException("{Invalid CSS dimension: '{$value}' (must be in <number><unit> format).");

        /** @phpstan-ignore-next-line */
        return $parsed;
    }

    public function setReadUnit(LengthUnit $readUnit): static
    {
        $this->readUnit = $readUnit;

        return $this;
    }

    public function value(LengthUnit $readUnit = null): float
    {
        if ($this->unit === $this->readUnit & is_null($readUnit)) {
            return $this->value;
        }

        if (! is_null($readUnit)) {
            return $this->value *
                static::ABSOLUTE_UNIT_EQUIVALENCE[$this->unit->value][$readUnit->value];
        }

        return $this->value *
            static::ABSOLUTE_UNIT_EQUIVALENCE[$this->unit->value][$this->readUnit->value];
    }

    public function convert(LengthUnit $readUnit): static
    {
        return (clone $this)->setReadUnit($readUnit);
    }

    public function toFloat(LengthUnit $readUnit = null): float
    {
        return $this->value($readUnit);
    }

    public function toString(LengthUnit $readUnit = null): string
    {
        return $this->value($readUnit).($readUnit?->value ?? $this->readUnit->value);
    }

    public function __toString(): string
    {
        return $this->value().$this->readUnit->value;
    }
}
