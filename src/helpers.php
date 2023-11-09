<?php

use Psr\Container\ContainerInterface;
use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\RendererFactory;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Support;
use RowBloom\RowBloom\Types\Context;

function rowBloom(ContainerInterface $container = null, Support $support = null, Config $defaultConfig = null, Options $defaultOptions = null): Context
{
    $support ??= new Support;

    $r = new RowBloom(
        $defaultOptions ?? new Options,
        $defaultConfig ?? new Config,
        new InterpolatorFactory($support, $container),
        new RendererFactory($support, $container),
        new DataLoaderFactory($support, $container),
    );

    return new Context($r, $support);
}
