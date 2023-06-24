<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use Exception;

final class RendererFactory
{
    // TODO: fix factory => only take driver as input
    public static function make(string $driver): RendererContract
    {
        $renderer = self::resolveDriver($driver);

        return new $renderer();
    }

    private static function resolveDriver(string $driver): string
    {
        return match ($driver) {
            'html' => HtmlRenderer::class,
            '*headless chrome' => PhpChromeRenderer::class,
            '*mpdf' => MpdfRenderer::class,
            // TODO: tcpdf
            default => throw new Exception("Unrecognized rendering driver {$driver}"),
        };
    }
}
