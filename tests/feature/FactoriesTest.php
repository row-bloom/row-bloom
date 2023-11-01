<?php

use RowBloom\RowBloom\BaseDriverFactory;
use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Renderers\RendererFactory;
use RowBloom\RowBloom\RowBloomException;

test('DataLoaderFactory::makeFromPath')
    ->expect(app()->make(DataLoaderFactory::class)->makeFromPath(mockJsonFile()))
    ->toBeInstanceOf(JsonDataLoader::class);

it('DataLoaderFactory::makeFromPath unsupported extension')
    ->expect(fn () => app()->make(DataLoaderFactory::class)->makeFromPath(__FILE__))
    ->throws(RowBloomException::class);

it('makes', function (BaseDriverFactory $factory, string $driverName, string $instanceOf) {
    expect($factory->make($driverName))->toBeInstanceOf($instanceOf);
})->with([
    [
        'factory' => app()->make(DataLoaderFactory::class),
        'driverName' => JsonDataLoader::NAME,
        'instanceOf' => JsonDataLoader::class,
    ],
    [
        'factory' => app()->make(DataLoaderFactory::class),
        'driverName' => FolderDataLoader::class,
        'instanceOf' => FolderDataLoader::class,
    ],
    [
        'factory' => app()->make(InterpolatorFactory::class),
        'driverName' => PhpInterpolator::NAME,
        'instanceOf' => PhpInterpolator::class,
    ],
    [
        'factory' => app()->make(RendererFactory::class),
        'driverName' => HtmlRenderer::NAME,
        'instanceOf' => HtmlRenderer::class,
    ],
]);
