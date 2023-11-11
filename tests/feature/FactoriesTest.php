<?php

use RowBloom\RowBloom\BaseDriverFactory;
use RowBloom\RowBloom\DataLoaders\Factory as DataLoadersFactory;
use RowBloom\RowBloom\DataLoaders\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Interpolators\Factory as InterpolatorsFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\Factory as RenderersFactory;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloomException;

test('DataLoadersFactory::makeFromLocation: Json')
    ->expect((new DataLoadersFactory(defaultSupport()))->makeFromLocation(mockJsonTableLocation()))
    ->toBeInstanceOf(JsonDataLoader::class);

test('DataLoadersFactory::makeFromLocation: Folder')
    ->expect((new DataLoadersFactory(defaultSupport()))->makeFromLocation(__DIR__))
    ->toBeInstanceOf(FolderDataLoader::class);

it('DataLoadersFactory::makeFromLocation unsupported extension')
    ->expect(fn () => (new DataLoadersFactory(defaultSupport()))->makeFromLocation(__FILE__))
    ->throws(RowBloomException::class);

it('makes', function (BaseDriverFactory $factory, string $driverName, string $instanceOf) {
    expect($factory->make($driverName))->toBeInstanceOf($instanceOf);
})->with([
    [
        'factory' => new DataLoadersFactory(defaultSupport()),
        'driverName' => JsonDataLoader::NAME,
        'instanceOf' => JsonDataLoader::class,
    ],
    [
        'factory' => new DataLoadersFactory(defaultSupport()),
        'driverName' => FolderDataLoader::class,
        'instanceOf' => FolderDataLoader::class,
    ],
    [
        'factory' => new InterpolatorsFactory(defaultSupport()),
        'driverName' => PhpInterpolator::NAME,
        'instanceOf' => PhpInterpolator::class,
    ],
    [
        'factory' => new RenderersFactory(defaultSupport()),
        'driverName' => HtmlRenderer::NAME,
        'instanceOf' => HtmlRenderer::class,
    ],
]);
