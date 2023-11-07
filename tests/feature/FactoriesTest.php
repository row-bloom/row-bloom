<?php

use Illuminate\Container\Container;
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
    ->expect(Container::getInstance()->get(DataLoaderFactory::class)->makeFromLocation(mockJsonTableLocation()))
    ->toBeInstanceOf(JsonDataLoader::class);

test('DataLoaderFactory::makeFromLocation: Folder')
    ->expect(Container::getInstance()->get(DataLoaderFactory::class)->makeFromLocation(__DIR__))
    ->toBeInstanceOf(FolderDataLoader::class);

it('DataLoaderFactory::makeFromLocation unsupported extension')
    ->expect(fn () => Container::getInstance()->get(DataLoaderFactory::class)->makeFromLocation(__FILE__))
    ->throws(RowBloomException::class);

it('makes', function (BaseDriverFactory $factory, string $driverName, string $instanceOf) {
    expect($factory->make($driverName))->toBeInstanceOf($instanceOf);
})->with([
    [
        'factory' => Container::getInstance()->get(DataLoaderFactory::class),
        'driverName' => JsonDataLoader::NAME,
        'instanceOf' => JsonDataLoader::class,
    ],
    [
        'factory' => Container::getInstance()->get(DataLoaderFactory::class),
        'driverName' => FolderDataLoader::class,
        'instanceOf' => FolderDataLoader::class,
    ],
    [
        'factory' => Container::getInstance()->get(InterpolatorFactory::class),
        'driverName' => PhpInterpolator::NAME,
        'instanceOf' => PhpInterpolator::class,
    ],
    [
        'factory' => Container::getInstance()->get(RendererFactory::class),
        'driverName' => HtmlRenderer::NAME,
        'instanceOf' => HtmlRenderer::class,
    ],
]);
