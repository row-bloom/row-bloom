<?php

use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use ElaborateCode\RowBloom\Interpolators\PhpInterpolator;
use ElaborateCode\RowBloom\Interpolators\TwigInterpolator;
use ElaborateCode\RowBloom\Renderers\MpdfRenderer;
use ElaborateCode\RowBloom\Renderers\PhpChromeRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
use ElaborateCode\RowBloom\Support;

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
