<?php

namespace RowBloom\CssSizing;

/**
 * .
 *
 * Resolve conflicting setting in the following order:
 *
 * 1. User specified paper format.
 * 2. User specified size.
 * 3. User specified width and height.
 * 4. Default to A4 paper format.
 *
 * > Page orientation (landscape) takes effect on user specified paper format and the default A4.
 */
class PaperSizeResolver
{
    public readonly ?PaperFormat $paperFormat;

    public readonly ?BoxSize $size;

    public readonly ?Length $width;

    public readonly ?Length $height;

    public static function init(
        ?PaperFormat $paperFormat = null,
        ?BoxSize $size = null,
        ?Length $width = null,
        ?Length $height = null,
        bool $landscape = false
    ): static {
        return new static($paperFormat, $size, $width, $height, $landscape);
    }

    final public function __construct(
        null|string|PaperFormat $paperFormat = null,
        null|array|BoxSize $size = null,
        null|string|Length $width = null,
        null|string|Length $height = null,
        public readonly bool $landscape = false
    ) {
        $this->paperFormat = $this->resolvePaperFormat($paperFormat);
        $this->size = $this->resolveSize($size);
        $this->width = $this->resolveWidth($width);
        $this->height = $this->resolveHeight($height);
    }

    public function resolve(): BoxSize
    {
        if (! is_null($this->paperFormat)) {
            $size = $this->paperFormat->size();

            return $this->landscape ? $size->toLandscape() : $size->toPortrait();
        }

        if (! is_null($this->size)) {
            return $this->size;
        }

        if (! is_null($this->width) && ! is_null($this->height)) {
            return new BoxSize($this->width, $this->height);
        }

        $size = PaperFormat::_A4->size();

        return $this->landscape ? $size->toLandscape() : $size->toPortrait();
    }

    protected function resolvePaperFormat(null|string|PaperFormat $paperFormat): ?PaperFormat
    {
        if (is_null($paperFormat) || $paperFormat instanceof PaperFormat) {
            return $paperFormat;
        }

        return PaperFormat::from($paperFormat);
    }

    protected function resolveSize(null|array|BoxSize $boxSize): ?BoxSize
    {
        if (is_null($boxSize) || $boxSize instanceof BoxSize) {
            return $boxSize;
        }

        return BoxSize::fromArray($boxSize);
    }

    protected function resolveWidth(null|string|Length $width): ?Length
    {
        if (is_null($width) || $width instanceof Length) {
            return $width;
        }

        return Length::fromDimension($width);
    }

    protected function resolveHeight(null|string|Length $height): ?Length
    {
        if (is_null($height) || $height instanceof Length) {
            return $height;
        }

        return Length::fromDimension($height);
    }
}
