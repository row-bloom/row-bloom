<?php

namespace ElaborateCode\RowBloom\Renderers;

enum Renderer: string
{
    case Html = HtmlRenderer::class;
    case Mpdf = MpdfRenderer::class;
    case PhpChrome = PhpChromeRenderer::class;
    // ? tcpdf

    public static function getDefault(): static
    {
        return self::Html;
    }
}
