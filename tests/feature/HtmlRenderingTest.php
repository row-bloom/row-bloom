<?php

use RowBloom\RowBloom\Interpolators\Interpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Renderers\Renderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

it('Basic html output', function (RowBloom $r, $css, $template, $table) {
    $r->setRenderer(Renderer::Html)->setInterpolator(Interpolator::Twig)
        ->addCss($css)
        ->setTemplate($template)
        ->addTable($table);

    expect($r->get())
        ->toBeString()
        ->toContain('ilies', 'mohamed');
})
    ->with([
        'Default' => app()->make(RowBloom::class),
        'Twig' => (app()->make(RowBloom::class))
            ->setInterpolator(Interpolator::Twig),
        'Html' => (app()->make(RowBloom::class))
            ->setRenderer(Renderer::Html),
        'HtmlRenderer instance' => (app()->make(RowBloom::class))
            ->setRenderer(app()->make(HtmlRenderer::class)),
    ])
    ->with([
        'primitives' => [
            'css' => '',
            'template' => '<h1>hey {{ name }}</h1>',
            'table' => [['name' => 'mohamed'], ['name' => 'ilies']],
        ],
        'types' => [
            'css' => Css::fromString(''),
            'template' => Html::fromString('<h1>hey {{ name }}</h1>'),
            'table' => Table::fromArray([['name' => 'mohamed'], ['name' => 'ilies']]),
        ],
    ]);
