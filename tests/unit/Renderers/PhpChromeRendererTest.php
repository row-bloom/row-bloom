<?php

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\PhpChromeRenderer;
use RowBloom\RowBloom\Renderers\RendererFactory;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

it('factorize')
    ->expect(fn () => app()->make(RendererFactory::class)->make(PhpChromeRenderer::NAME))
    ->toBeInstanceOf(PhpChromeRenderer::class);

it('renders and get (basic)')
    ->with([
        'example 1' => [
            'template' => Html::fromString('<h1>Title</h1><p>Bold text</p><div>Normal text</div>'),
            'css' => Css::fromString('p {font-weight: bold;}'),
            'options' => app()->make(Options::class),
            'config' => app()->make(Config::class),
        ],
    ])
    ->expect(function ($template, $css, $options, $config) {
        return app()->make(RendererFactory::class)->make(PhpChromeRenderer::NAME)
            ->render($template, $css, $options, $config)->get();
    })
    // ? more assertions
    ->toBeString();
