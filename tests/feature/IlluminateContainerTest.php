<?php

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use RowBloom\RowBloom\DataLoaders\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Support;

test('Container', function () {
    Container::getInstance()->singleton(ContainerInterface::class, fn() => Container::getInstance());

    Container::getInstance()->singleton(Support::class);
    Container::getInstance()->singleton(DataLoaderFactory::class);
    Container::getInstance()->singleton(InterpolatorFactory::class);
    Container::getInstance()->singleton(RendererFactory::class);

    /** @var Support */
    $support = Container::getInstance()->get(Support::class);

    $support->registerDataLoaderDriver(FolderDataLoader::NAME, FolderDataLoader::class)
        ->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class)
        ->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class)
        ->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);

    /** @var RowBloom */
    $r = Container::getInstance()->get(RowBloom::class);
    $renderingString = $r->setRenderer('HTML')
        ->setInterpolator(PhpInterpolator::NAME)
        ->addCss('')
        ->setTemplate('')
        ->addTable([[]])
        ->get();

    expect($renderingString)->toBeString();
});
