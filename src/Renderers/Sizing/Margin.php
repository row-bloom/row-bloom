<?php

namespace RowBloom\RowBloom\Renderers\Sizing;

use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\RowBloomException;

final class Margin
{
    private static LengthUnit $defaultUnit = LengthUnit::MILLIMETER;

    private LengthUnit $unit;

    /** @var array{marginTop: Length, marginRight: Length, marginBottom: Length, marginLeft: Length} */
    private array $value;

    public static function fromOptions(Options $options, LengthUnit $unit = null): static
    {
        return new self($options->margin, $unit);
    }

    final public function __construct(array|string $margin, LengthUnit $unit = null)
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
        if (count($margin) === 0 || count($margin) > 4) {
            throw new RowBloomException('Invalid margin');
        }

        $this->value = match (count($margin)) {
            1 => [
                'marginTop' => Length::fromValue($margin[0], $this->unit),
                'marginRight' => Length::fromValue($margin[0], $this->unit),
                'marginBottom' => Length::fromValue($margin[0], $this->unit),
                'marginLeft' => Length::fromValue($margin[0], $this->unit),
            ],
            2 => [
                'marginTop' => Length::fromValue($margin[0], $this->unit),
                'marginRight' => Length::fromValue($margin[1], $this->unit),
                'marginBottom' => Length::fromValue($margin[0], $this->unit),
                'marginLeft' => Length::fromValue($margin[1], $this->unit),
            ],
            3 => [
                'marginTop' => Length::fromValue($margin[0], $this->unit),
                'marginRight' => Length::fromValue($margin[1], $this->unit),
                'marginBottom' => Length::fromValue($margin[2], $this->unit),
                'marginLeft' => Length::fromValue($margin[1], $this->unit),
            ],
            4 => [
                'marginTop' => Length::fromValue($margin[0], $this->unit),
                'marginRight' => Length::fromValue($margin[1], $this->unit),
                'marginBottom' => Length::fromValue($margin[2], $this->unit),
                'marginLeft' => Length::fromValue($margin[3], $this->unit),
            ],
        };
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

    /** @return array{marginTop: Length, marginRight: Length, marginBottom: Length, marginLeft: Length} */
    public function all(): array
    {
        return $this->value;
    }

    /** @return array{marginTop: Length, marginRight: Length, marginBottom: Length, marginLeft: Length} */
    public function allIn(LengthUnit $to): array
    {
        return array_map(
            fn (Length $v) => $v->convert($to),
            $this->value
        );
    }

    /** @return array{marginTop: float, marginRight: float, marginBottom: float, marginLeft: float} */
    public function allRaw(): array
    {
        return array_map(
            fn (Length $v) => $v->value(),
            $this->value
        );
    }

    /** @return array{marginTop: float, marginRight: float, marginBottom: float, marginLeft: float} */
    public function allRawIn(LengthUnit $to): array
    {
        return array_map(
            fn (Length $v) => $v->convert($to)->value(),
            $this->value
        );
    }
}
