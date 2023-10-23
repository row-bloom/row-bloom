<?php

use RowBloom\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Interpolators\TwigInterpolator;
use RowBloom\RowBloom\Renderers\MpdfRenderer;
use RowBloom\RowBloom\Renderers\PhpChromeRenderer;
use RowBloom\RowBloom\Renderers\Renderer;
use RowBloom\RowBloom\Support;

it('lists capabilities', function () {
    /** @var Support */
    $support = app()->get(Support::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json', 'csv', 'xlsx']);

    expect($support->getDataCollectorDrivers())
        ->toHaveKeys(['Spreadsheet', 'Folder', 'Json'])
        ->toContain(SpreadsheetDataCollector::class);

    expect($support->getInterpolatorDrivers())
        ->toHaveKeys(['Php', 'Twig'])
        ->toContain(TwigInterpolator::class, PhpInterpolator::class);

    expect($support->getRendererDrivers())
        ->toHaveKeys(['Mpdf', 'Html', 'PhpChrome'])
        ->toContain(PhpChromeRenderer::class, MpdfRenderer::class);

    expect($support->getRendererOptionsSupport(Renderer::Html)['metadataKeywords'])
        ->toBeFalse();

    expect($support->getRendererOptionsSupport(Renderer::Mpdf)['metadataKeywords'])
        ->toBeTrue();

    expect($support->getRendererOptionsSupport('yo'))
        ->toBeNull();
});
