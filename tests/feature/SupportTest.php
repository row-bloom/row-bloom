<?php

use RowBloom\RowBloom\DataCollectors\Json\JsonDataCollector;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Support;

it('lists capabilities', function () {
    /** @var Support */
    $support = app()->get(Support::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json']);

    expect($support->getDataCollectorDrivers())
        ->toHaveKeys(['Folder', JsonDataCollector::NAME])
        ->toContain(JsonDataCollector::class);

    expect($support->getInterpolatorDrivers())
        ->toHaveKeys(['PHP'])
        ->toContain(PhpInterpolator::class);

    expect($support->getRendererDrivers())
        ->toHaveKeys(['HTML'])
        ->toContain(HtmlRenderer::class);

    expect($support->getRendererOptionsSupport(HtmlRenderer::NAME)['metadataKeywords'])
        ->toBeFalse();

    expect($support->getRendererOptionsSupport('yo'))
        ->toHaveCount(0);
});

it('remove and register data collector', function () {
    /** @var Support */
    $support = app()->get(Support::class);

    $support->removeDataCollectorDriver(JsonDataCollector::NAME);

    expect($support->getSupportedTableFileExtensions())
        ->toBeArray()
        ->not->toHaveKeys(['json']);

    $support->registerDataCollectorDriver(JsonDataCollector::NAME, JsonDataCollector::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json']);
});
