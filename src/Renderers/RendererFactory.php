<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use Exception;

final class RendererFactory
{
    public static $defaultDriver = 'html';

    public static function make(?string $driver = null): RendererContract
    {
        $driver ??= self::$defaultDriver;

        $renderer = self::resolveDriver($driver);

        if ($renderer) {
            return new $renderer;
        }

        if (class_exists($driver) && in_array(RendererContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid renderer");
    }

    private static function resolveDriver(string $driver): ?string
    {
        return match ($driver) {
            'html' => HtmlRenderer::class,
            '*php chrome' => PhpChromeRenderer::class,
            '*mpdf' => MpdfRenderer::class,
            // ? tcpdf
            default => null,
        };
    }
}
