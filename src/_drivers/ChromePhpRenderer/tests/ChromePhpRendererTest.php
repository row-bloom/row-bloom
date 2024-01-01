<?php

use RowBloom\ChromePhpRenderer\ChromePhpRenderer;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

it('renders and get (basic)', function (Html $template, Css $css) {
    $rendering = (new ChromePhpRenderer)->render($template, $css, new Options)
        ->get();

    expect($rendering)->toBeString();

    // The output is binary PDF format encoded in base64.
    // ? how to do more assertions
})
    ->with([
        'example 1' => [
            'template' => Html::fromString('Lorem ipsum dolores'),
            'css' => Css::fromString(''),
        ],
        'example 2' => [
            'template' => Html::fromString('<h1>Title</h1><p>Bold text</p><div>Normal text</div>'),
            'css' => Css::fromString('p {font-weight: bold;}'),
        ],
    ]);
