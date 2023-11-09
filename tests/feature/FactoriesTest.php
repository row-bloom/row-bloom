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

test('DataLoaderFactory::makeFromLocation: Json')
    ->expect((new DataLoaderFactory(defaultSupport()))->makeFromLocation(mockJsonTableLocation()))
    ->toBeInstanceOf(JsonDataLoader::class);

test('DataLoaderFactory::makeFromLocation: Folder')
    ->expect((new DataLoaderFactory(defaultSupport()))->makeFromLocation(__DIR__))
    ->toBeInstanceOf(FolderDataLoader::class);

it('DataLoaderFactory::makeFromLocation unsupported extension')
    ->expect(fn () => (new DataLoaderFactory(defaultSupport()))->makeFromLocation(__FILE__))
    ->throws(RowBloomException::class);

it('makes', function (BaseDriverFactory $factory, string $driverName, string $instanceOf) {
    expect($factory->make($driverName))->toBeInstanceOf($instanceOf);
})->with([
    [
        'factory' => new DataLoaderFactory(defaultSupport()),
        'driverName' => JsonDataLoader::NAME,
        'instanceOf' => JsonDataLoader::class,
    ],
    [
        'factory' => new DataLoaderFactory(defaultSupport()),
        'driverName' => FolderDataLoader::class,
        'instanceOf' => FolderDataLoader::class,
    ],
    [
        'factory' => new InterpolatorFactory(defaultSupport()),
        'driverName' => PhpInterpolator::NAME,
        'instanceOf' => PhpInterpolator::class,
    ],
    [
        'factory' => new RendererFactory(defaultSupport()),
        'driverName' => HtmlRenderer::NAME,
        'instanceOf' => HtmlRenderer::class,
    ],
]);
