<?php

use RowBloom\BrowsershotRenderer\BrowsershotRenderer;
use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Options;
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
    ->expect(fn (Html $template, Css $css, Options $options, Config $config) => (new BrowsershotRenderer)->render($template, $css, $options, $config)->get()
    )
    // ? more assertions
    ->toBeString();
