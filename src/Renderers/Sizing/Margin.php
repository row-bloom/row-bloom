<?php

namespace RowBloom\RowBloom\Renderers\Sizing;

use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\RowBloomException;

final class Margin
{
    private static LengthUnit $defaultUnit = LengthUnit::MILLIMETER;

    private LengthUnit $unit;

    /** @var Length[] */
    private array $value = [];

    public static function fromOptions(Options $options, LengthUnit $unit = null): static
    {
        return new self($options->margin, $unit);
    }

    public function __construct(array|string $margin, LengthUnit $unit = null)
    {
        $this->unit = $unit ?? self::$defaultUnit;

        if (is_string($margin)) {
            if (preg_match('/\d+(?:\.\d+)?(?:\s+[[:alpha:]]+)?/', $margin, $parsedMargin) === false) {
                throw new RowBloomException("Invalid margin {$margin}");
            }

            $margin = $parsedMargin;
        }

        $this->setValue($margin);
    }

    private function setValue(array $margin): void
    {
        switch (count($margin)) {
            case 1:
                $this->setLength('marginTop', $margin[0]);
                $this->setLength('marginRight', $margin[0]);
                $this->setLength('marginBottom', $margin[0]);
                $this->setLength('marginLeft', $margin[0]);

                return;
            case 2:
                $this->setLength('marginTop', $margin[0]);
                $this->setLength('marginRight', $margin[1]);
                $this->setLength('marginBottom', $margin[0]);
                $this->setLength('marginLeft', $margin[1]);

                return;
            case 3:
                $this->setLength('marginTop', $margin[0]);
                $this->setLength('marginRight', $margin[1]);
                $this->setLength('marginBottom', $margin[2]);
                $this->setLength('marginLeft', $margin[1]);

                return;
            case 4:
                $this->setLength('marginTop', $margin[0]);
                $this->setLength('marginRight', $margin[1]);
                $this->setLength('marginBottom', $margin[2]);
                $this->setLength('marginLeft', $margin[3]);

                return;
        }

        throw new RowBloomException('Invalid margin');
    }

    private function setLength(string $key, int|float|string $value): void
    {
        $this->value[$key] = Length::fromValue($value, $this->unit);
    }

    // ============================================================
    // Getters
    // ============================================================
    // TODO: use a formatter class (Length collection?)

    public function get(string $key): ?Length
    {
        return $this->value[$key] ?? null;
    }

    public function getIn(string $key, LengthUnit $to): ?Length
    {
        return $this->get($key)?->convert($to);
    }

    public function getRaw(string $key): ?float
    {
        return $this->get($key)?->value();
    }

    public function getRawIn(string $key, LengthUnit $to): ?float
    {
        return $this->get($key)?->convert($to)->value();
    }

    public function all(): array
    {
        return $this->value;
    }

    public function allIn(LengthUnit $to): array
    {
        return array_map(
            fn (Length $v) => $v->convert($to),
            $this->value
        );
    }

    public function allRaw(): array
    {
        return array_map(
            fn (Length $v) => $v->value(),
            $this->value
        );
    }

    public function allRawIn(LengthUnit $to): array
    {
        return array_map(
            fn (Length $v) => $v->convert($to)->value(),
            $this->value
        );
    }
}
