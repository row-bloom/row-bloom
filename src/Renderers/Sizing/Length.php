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
        // TODO: Q
    ];

    public static function fromDimension(string $value): static
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

        if ($value === '0') {
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
