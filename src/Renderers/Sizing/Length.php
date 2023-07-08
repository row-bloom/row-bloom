<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

use ElaborateCode\RowBloom\RowBloomException;

final class Length
{
    /**
     * @var (int|float)[][] Absolute units only
     *
     * ? enum keys
     */
    private const RATIOS_TABLE = [
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

    public static function fromValue(int|float|string $value, LengthUnit $readUnit): self
    {
        if (is_numeric($value)) {
            return Length::fromNumber($value, $readUnit);
        }

        return Length::fromString($value, $readUnit);
    }

    public static function fromNumber(float|int|string $value, LengthUnit $readUnit, ?LengthUnit $sourceUnit = null): self
    {
        if (! is_numeric($value)) {
            throw new RowBloomException("Not numeric value '{$value}'");
        }

        $sourceUnit ??= $readUnit;

        return new self((float) $value, $readUnit, $sourceUnit);
    }

    public static function fromString(string $value, LengthUnit $readUnit): self
    {
        $value = trim((string) $value);

        if (is_numeric($value)) {
            return Length::fromNumber($value, $readUnit);
        }

        if (preg_match('/^(?<v>\d+(\.\d+)?)\s+(?<u>[[:alpha:]]+)$/', $value, $parsed)) {
            return Length::fromNumber($parsed['v'], LengthUnit::from($parsed['u']))
                ->setUnit($readUnit);
        }

        throw new RowBloomException("Invalid value '{$value}'");
    }

    private function __construct(
        private readonly float $value,
        private LengthUnit $readUnit,
        private readonly LengthUnit $sourceUnit
    ) {
        // TODO: if relative unit require a reference
    }

    public function setUnit(LengthUnit $readUnit): self
    {
        $this->readUnit = $readUnit;

        return $this;
    }

    public function convert(LengthUnit $readUnit): self
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

        return $this->value * self::RATIOS_TABLE[$this->sourceUnit->value][$readUnit->value];
    }
}
