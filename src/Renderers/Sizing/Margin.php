<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

use ElaborateCode\RowBloom\Options;
use Exception;

final class Margin
{
    private static string $defaultUnit = Length::MILLIMETER_UNIT;

    private string $unit;

    /** @var (float|int|string)[] */
    private array $value = [];

    public static function fromOptions(Options $options, ?string $unit = null)
    {
        return new self($options->margins, $unit);
    }

    public function __construct(array|string $margin, ?string $unit = null)
    {
        $this->unit = $unit ?? self::$defaultUnit;

        if (is_string($margin)) {
            if (preg_match('/\d+(?:\.\d+)?(?:\s+[[:alpha:]]+)?/', $margin, $parsedMargin) === false) {
                throw new Exception("Invalid margin {$margin}");
            }

            $margin = $parsedMargin;
        }

        $count = count($margin);

        if ($count === 1) {
            $this->set('marginTop', $margin[0]);
            $this->set('marginRight', $margin[0]);
            $this->set('marginBottom', $margin[0]);
            $this->set('marginLeft', $margin[0]);
        } elseif ($count === 2) {
            $this->set('marginTop', $margin[0]);
            $this->set('marginRight', $margin[1]);
            $this->set('marginBottom', $margin[0]);
            $this->set('marginLeft', $margin[1]);
        } elseif ($count === 4) {
            $this->set('marginTop', $margin[0]);
            $this->set('marginRight', $margin[1]);
            $this->set('marginBottom', $margin[2]);
            $this->set('marginLeft', $margin[3]);
        } else {
            throw new Exception('Invalid margin');
        }
    }

    private function set(string $key, int|float|string $value): void
    {
        $value = trim($value);

        if (preg_match('/^\d+(\.\d+)?$/', $value)) {
        } elseif (preg_match('/^(?<value>\d+(\.\d+)?)\s+(?<unit>[[:alpha:]]+)$/', $value, $parsed)) {
            $value = (new Length($parsed['value'], $parsed['unit']))
                ->convert($this->unit)->value();
        } else {
            throw new Exception('Invalid margin');
        }

        $this->value[$key] = (float) $value;
    }

    public function get(string $key): ?float
    {
        return $this->value[$key] ?? null;
    }

    public function getIn(string $key, string $to): ?float
    {
        if (! isset($this->value[$key])) {
            return null;
        }

        return (new Length($this->value[$key], $to))
            ->convert($this->unit)->value();
    }

    // TODO: rename this to allRaw()? all() returns string of "<val> <unit>"
    public function all(): array
    {
        return $this->value;
    }

    public function allIn(string $to): array
    {
        return array_map(
            fn ($v) => (new Length($v, $this->unit))->convert($to)->value(),
            $this->value
        );
    }
}
