<?php

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

it('renders and get (basic)')
    ->with([
        'example 1' => [
            'template' => Html::fromString('<h1>Title</h1><p>Bold text</p><div>Normal text</div>'),
            'css' => Css::fromString('p {font-weight: bold;}'),
            'options' => new Options,
            'config' => new Config,
        ],
    ])
    ->expect(function ($template, $css, $options, $config) {
        return (new HtmlRenderer)->render($template, $css, $options, $config)->get();
    })
    // ? more assertions
    ->toBeString();
