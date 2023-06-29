<?php

use ElaborateCode\RowBloom\Interpolators\Interpolator;
use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
use ElaborateCode\RowBloom\RowBloom;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;

it('Basic html output', function (RowBloom $r, $css, $template, $table) {
    $r->addCss($css)->setTemplate($template)->addTable($table);

    expect($r->get())
        ->toBeString()
        ->toContain('ilies', 'mohamed');
})
    ->with([
        'Default' => new RowBloom,
        'Twig' => (new RowBloom)->setInterpolator(Interpolator::Twig),
        'Html' => (new RowBloom)->setRenderer(Renderer::Html),
        'HtmlRenderer instance' => (new RowBloom)->setRenderer(new HtmlRenderer),
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
