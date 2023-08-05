<?php

namespace ElaborateCode\RowBloom\Renderers;

enum Renderer: string
{
    case Html = HtmlRenderer::class;
    case Mpdf = MpdfRenderer::class;
    case PhpChrome = PhpChromeRenderer::class;
    case Browsershot = BrowsershotRenderer::class;
    // ? tcpdf
}
