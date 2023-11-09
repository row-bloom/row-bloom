<?php

use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

test('Basic html output', function (RowBloom $r, $css, $template, $table) {
    $renderingString = $r->setInterpolator(PhpInterpolator::NAME)
        ->addCss($css)
        ->setTemplate($template)
        ->addTable($table)
        ->get();

    expect($renderingString)->toBeString()->toContain('ilies', 'mohamed');
})
    ->with([
        'HTML render by name' => defaultRowBloom()->setRenderer(HtmlRenderer::NAME),
        'HTML render by class' => defaultRowBloom()->setRenderer(HtmlRenderer::class),
        'HTML renderer by instance' => defaultRowBloom()->setRenderer(new HtmlRenderer),
    ])
    ->with([
        'primitives' => [
            'css' => '',
            'template' => '<h1>hey <?= $name ?> </h1>',
            'table' => [['name' => 'mohamed'], ['name' => 'ilies']],
        ],
        'types' => [
            'css' => Css::fromString(''),
            'template' => Html::fromString('<h1>hey <?= $name ?> </h1>'),
            'table' => Table::fromArray([['name' => 'mohamed'], ['name' => 'ilies']]),
        ],
    ]);

test('setFromArray()')
    ->expect(defaultRowBloom()->setFromArray([
        'renderer' => 'HTML',
        'interpolator' => 'PHP',
        'css' => '',
        'template' => '<h1>hey <?= $name ?> </h1>',
        'table' => [['name' => 'mohamed'], ['name' => 'ilies']],
        'options' => [
            'raw_header' => 'the Prime',
        ],
    ])->get())
    ->toBeString()
    ->toContain('ilies', 'mohamed');
