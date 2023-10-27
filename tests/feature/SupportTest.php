<?php

namespace RowBloom\RowBloom\Tests\feature\SupportTest;

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

it('picks json data collector based on priority', function () {
    /** @var Support */
    $support = app()->get(Support::class);

    $support->removeDataCollectorDriver(JsonDataCollector::NAME);
    expect($support->getFileExtensionDataCollectorDriver('json'))->toBeNull();

    $support->registerDataCollectorDriver(DummyDataCollector99::NAME, DummyDataCollector99::class);
    expect($support->getFileExtensionDataCollectorDriver('json'))->toBe(DummyDataCollector99::class);

    $support->registerDataCollectorDriver(DummyDataCollector101::NAME, DummyDataCollector101::class);
    expect($support->getFileExtensionDataCollectorDriver('json'))->toBe(DummyDataCollector101::class);

    // ! cleanup. shouldn't be necessary
    $support->registerDataCollectorDriver(JsonDataCollector::NAME, JsonDataCollector::class);
    $support->removeDataCollectorDriver(DummyDataCollector99::NAME);
    $support->removeDataCollectorDriver(DummyDataCollector101::NAME);
});

class DummyDataCollector99 extends JsonDataCollector
{
    public const NAME = 'Dummy99';

    public static function getSupportedFileExtensions(): array
    {
        return [
            'json' => 99,
        ];
    }
}

class DummyDataCollector101 extends JsonDataCollector
{
    public const NAME = 'Dummy101';

    public static function getSupportedFileExtensions(): array
    {
        return [
            'json' => 101,
        ];
    }
}
