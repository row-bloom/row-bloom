<?php

namespace RowBloom\RowBloom\Renderers\Sizing;

use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\RowBloomException;

final class Margin
{
    protected static LengthUnit $defaultUnit = LengthUnit::MILLIMETER;

    protected readonly LengthUnit $unit;

    /** @var array{top: Length, right: Length, bottom: Length, left: Length} */
    protected readonly array $value;

    public static function fromOptions(Options $options, LengthUnit $unit = null): static
    {
        return new self($options->margin, $unit);
    }

    final public function __construct(array|string $margin, LengthUnit $unit = null)
    {
        $this->unit = $unit ?? self::$defaultUnit;

        if (is_string($margin)) {
            $margin = $this->parseStringMargin($margin);
        }

        if (count($margin) === 0 || count($margin) > 4) {
            throw new RowBloomException('Invalid margin: '.json_encode($margin));
        }

        $this->value = match (count($margin)) {
            1 => [
                'top' => Length::fromValue($margin[0], $this->unit),
                'right' => Length::fromValue($margin[0], $this->unit),
                'bottom' => Length::fromValue($margin[0], $this->unit),
                'left' => Length::fromValue($margin[0], $this->unit),
            ],
            2 => [
                'top' => Length::fromValue($margin[0], $this->unit),
                'right' => Length::fromValue($margin[1], $this->unit),
                'bottom' => Length::fromValue($margin[0], $this->unit),
                'left' => Length::fromValue($margin[1], $this->unit),
            ],
            3 => [
                'top' => Length::fromValue($margin[0], $this->unit),
                'right' => Length::fromValue($margin[1], $this->unit),
                'bottom' => Length::fromValue($margin[2], $this->unit),
                'left' => Length::fromValue($margin[1], $this->unit),
            ],
            4 => [
                'top' => Length::fromValue($margin[0], $this->unit),
                'right' => Length::fromValue($margin[1], $this->unit),
                'bottom' => Length::fromValue($margin[2], $this->unit),
                'left' => Length::fromValue($margin[3], $this->unit),
            ],
        };
    }

    protected function parseStringMargin(string $margin): array
    {
        if (preg_match('/\d+(?:\.\d+)?(?:\s+[[:alpha:]]+)?/', $margin, $parsedMargin) === false) {
            throw new RowBloomException("Invalid string margin {$margin}");
        }

        return $parsedMargin;
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

    /** @return array{top: Length, right: Length, bottom: Length, left: Length} */
    public function all(): array
    {
        return $this->value;
    }

    /** @return array{top: Length, right: Length, bottom: Length, left: Length} */
    public function allIn(LengthUnit $to): array
    {
        return array_map(
            fn (Length $v) => $v->convert($to),
            $this->value
        );
    }

    /** @return array{top: float, right: float, bottom: float, left: float} */
    public function allRaw(): array
    {
        return array_map(
            fn (Length $v) => $v->value(),
            $this->value
        );
    }

    /** @return array{top: float, right: float, bottom: float, left: float} */
    public function allRawIn(LengthUnit $to): array
    {
        return array_map(
            fn (Length $v) => $v->convert($to)->value(),
            $this->value
        );
    }
}
