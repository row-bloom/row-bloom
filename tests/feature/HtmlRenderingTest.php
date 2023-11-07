<?php

use Illuminate\Container\Container;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

test('Basic html output', function (RowBloom $r, $css, $template, $table) {
    $r->setRenderer(HtmlRenderer::NAME)->setInterpolator(PhpInterpolator::NAME)
        ->addCss($css)
        ->setTemplate($template)
        ->addTable($table);

    expect($r->get())->toBeString()->toContain('ilies', 'mohamed');
})
    ->with([
        'Default' => Container::getInstance()->get(RowBloom::class),
        'HTML render by name' => (Container::getInstance()->get(RowBloom::class))
            ->setRenderer(HtmlRenderer::NAME),
        'HTML renderer by class' => (Container::getInstance()->get(RowBloom::class))
            ->setRenderer(Container::getInstance()->get(HtmlRenderer::class)),
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
    ->expect(Container::getInstance()->get(RowBloom::class)->setFromArray([
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
