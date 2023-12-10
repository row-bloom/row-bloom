<?php

namespace RowBloom\RowBloom\Renderers\Sizing;

class PageSizeResolver
{
    public static function resolve(
        PaperFormat $paperFormat = null,
        BoxSize $size = null,
        Length $width = null,
        Length $height = null,
        bool $landscape = false
    ): BoxSize {
        if (isset($paperFormat)) {
            $size = $paperFormat->size();

            return $landscape ? $size->toLandscape() : $size;
        }

        if (! is_null($size)) {
            return $size;
        }

        if (! is_null($width) && ! is_null($height)) {
            return new BoxSize($width, $height);
        }

        $size = PaperFormat::_A4->size();

        return $landscape ? $size->toLandscape() : $size;
    }
}
