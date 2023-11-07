<?php

namespace RowBloom\RowBloom\Tests\feature\SupportTest;

use Illuminate\Container\Container;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Support;

it('lists capabilities', function () {
    /** @var Support */
    $support = Container::getInstance()->get(Support::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json']);

    expect($support->getDataLoaderDrivers())
        ->toHaveKeys(['Folder', JsonDataLoader::NAME])
        ->toContain(JsonDataLoader::class);

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

it('remove and register data loader', function () {
    /** @var Support */
    $support = Container::getInstance()->get(Support::class);

    $support->removeDataLoaderDriver(JsonDataLoader::NAME);

    expect($support->getSupportedTableFileExtensions())
        ->toBeArray()
        ->not->toHaveKeys(['json']);

    $support->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json']);
});

it('picks json data loader based on priority', function () {
    /** @var Support */
    $support = Container::getInstance()->get(Support::class);

    $support->removeDataLoaderDriver(JsonDataLoader::NAME);
    expect($support->getFileExtensionDataLoaderDriver('json'))->toBeNull();

    $support->registerDataLoaderDriver(DummyDataLoader99::NAME, DummyDataLoader99::class);
    expect($support->getFileExtensionDataLoaderDriver('json'))->toBe(DummyDataLoader99::class);

    $support->registerDataLoaderDriver(DummyDataLoader101::NAME, DummyDataLoader101::class);
    expect($support->getFileExtensionDataLoaderDriver('json'))->toBe(DummyDataLoader101::class);
});

class DummyDataLoader99 extends JsonDataLoader
{
    public const NAME = 'Dummy99';

    public static function getSupportedFileExtensions(): array
    {
        return [
            'json' => 99,
        ];
    }
}

class DummyDataLoader101 extends JsonDataLoader
{
    public const NAME = 'Dummy101';

    public static function getSupportedFileExtensions(): array
    {
        return [
            'json' => 101,
        ];
    }
}
