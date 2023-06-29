<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Utils\BasicSingletonConcern;
use Exception;

final class RendererFactory
{
    use BasicSingletonConcern;

    public static $defaultDriver = Renderer::Html;

    public function make(Renderer|string|null $driver = null): RendererContract
    {
        $driver ??= self::$defaultDriver;

        if ($driver instanceof Renderer) {
            $renderer = $this->resolveDriver($driver);

            return new $renderer;
        }

        if (class_exists($driver) && in_array(RendererContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid renderer");
    }

    private function resolveDriver(Renderer $driver): string
    {
        return match ($driver) {
            Renderer::Html => HtmlRenderer::class,
            Renderer::PhpChrome => PhpChromeRenderer::class,
            Renderer::Mpdf => MpdfRenderer::class,
            // ? tcpdf
        };
    }
}
