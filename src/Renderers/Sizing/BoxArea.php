<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_box_model/Introduction_to_the_CSS_box_model
 */

namespace RowBloom\RowBloom\Renderers\Sizing;

use RowBloom\RowBloom\RowBloomException;

class BoxArea
{
    public readonly Length $top;

    public readonly Length $right;

    public readonly Length $bottom;

    public readonly Length $left;

    public static function new(string|array $value): static
    {
        return new static($value);
    }

    final public function __construct(string|array $value)
    {
        if (is_string($value)) {
            $value = static::splitValueComponents($value);
        }

        if (array_is_list($value)) {
            $value = static::mapValueComponents($value);
        } elseif (
            ! isset($value['top']) ||
            ! isset($value['right']) ||
            ! isset($value['bottom']) ||
            ! isset($value['left'])
        ) {
            throw new RowBloomException(json_encode($value).' must contain all the following keys: top, right, bottom, left.');
        }

        $this->top = Length::fromDimension($value['top']);
        $this->right = Length::fromDimension($value['right']);
        $this->bottom = Length::fromDimension($value['bottom']);
        $this->left = Length::fromDimension($value['left']);
    }

    /**
     * @return string[]
     *
     * @see https://www.w3.org/TR/css-values-3/#component-whitespace
     */
    public static function splitValueComponents(string $value): array
    {
        $value = trim($value);

        $valueComponents = preg_split('/\s/', $value);

        if ($valueComponents === false) {
            throw new RowBloomException("Couldn't parse: {$value}");
        }

        if (count($valueComponents) === 0 || count($valueComponents) > 4) {
            throw new RowBloomException("{$value} must contain 1 to 4 components");
        }

        return $valueComponents;
    }

    /**
     * @param  string[]  $valueComponents
     * @return array{top: string, right: string, bottom: string, left: string}
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/margin#syntax
     */
    public static function mapValueComponents(array $valueComponents): array
    {
        $componentsCount = count($valueComponents);

        if (! array_is_list($valueComponents) || $componentsCount === 0 || $componentsCount > 4) {
            throw new RowBloomException(json_encode($valueComponents).' must be a list with max index of 3');
        }

        $labeledValue = [];

        $labeledValue['top'] = $valueComponents[0];

        $labeledValue['right'] = match ($componentsCount) {
            1 => $valueComponents[0],
            2,3,4 => $valueComponents[1],
        };

        $labeledValue['bottom'] = match ($componentsCount) {
            1,2 => $valueComponents[0],
            3,4 => $valueComponents[2],
        };

        $labeledValue['left'] = match ($componentsCount) {
            1 => $valueComponents[0],
            2,3 => $valueComponents[1],
            4 => $valueComponents[3],
        };

        return $labeledValue;
    }

    // ============================================================
    // Getters
    // ============================================================

    /** @return array{top: Length, right: Length, bottom: Length, left: Length} */
    public function toMap(): array
    {
        return [
            'top' => $this->top,
            'right' => $this->right,
            'bottom' => $this->bottom,
            'left' => $this->left,
        ];
    }

    /** @return array{top: string, right: string, bottom: string, left: string} */
    public function toStringsMap(?LengthUnit $reaUnit = null): array
    {
        return [
            'top' => $this->top->toString($reaUnit),
            'right' => $this->right->toString($reaUnit),
            'bottom' => $this->bottom->toString($reaUnit),
            'left' => $this->left->toString($reaUnit),
        ];
    }

    /** @return Length[] */
    public function toList(): array
    {
        return array_values($this->toMap());
    }

    /** @return string[] */
    public function toStringsList(?LengthUnit $reaUnit = null): array
    {
        return array_values($this->toStringsMap($reaUnit));
    }
}
