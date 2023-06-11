<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use Exception;

class RendererFactory
{
    // TODO: fix factory => only take driver as input
    public static function make(
        string $driver,
        InterpolatedTemplate $template,
        Css $css,
        array $options = []
    ): RendererContract {
        $renderer = static::resolveDriver($driver);

        return new $renderer($template, $css, $options);
    }

    protected static function resolveDriver(string $driver): string
    {
        return match ($driver) {
            'html' => HtmlRenderer::class,
            'chromium-pdf' => PhpChromeRenderer::class,
            'html-to-pdf' => MpdfRenderer::class,
            // TODO: tcpdf
            default => throw new Exception("Unrecognized rendering driver {$driver}"),
        };
    }
}
