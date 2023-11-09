<?php

use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

test('Basic html output', function (RowBloom $r, $css, $template, $table) {
    $r->setInterpolator(PhpInterpolator::NAME)
        ->addCss($css)
        ->setTemplate($template)
        ->addTable($table);

    expect($r->get())->toBeString()->toContain('ilies', 'mohamed');
})
    ->with([
        'HTML render by name' => defaultRowBloom(),
        'HTML render by name' => defaultRowBloom()->setRenderer(HtmlRenderer::NAME),
        'HTML renderer by class' => defaultRowBloom()->setRenderer(new HtmlRenderer),
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
