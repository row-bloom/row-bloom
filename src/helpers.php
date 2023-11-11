<?php

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\DataLoaders\Factory as DataLoadersFactory;
use RowBloom\RowBloom\Interpolators\Factory as InterpolatorsFactory;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\Factory as RenderersFactory;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Support;
use RowBloom\RowBloom\Types\Context;

function rowBloom(Support $support = null, Config $defaultConfig = null, Options $defaultOptions = null): Context
{
    $support ??= new Support;

    $r = new RowBloom(
        $defaultOptions ?? new Options,
        $defaultConfig ?? new Config,
        new InterpolatorsFactory($support),
        new RenderersFactory($support),
        new DataLoadersFactory($support),
    );

    return new Context($r, $support);
}
