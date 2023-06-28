<?php

use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\RowBloom;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

it('renders using Twig and HtmlRenderer', function (RowBloom $r) {
    $css = new Css('');
    $template = new Template('<h1>hey {{ name }}</h1>');
    $table = Table::fromArray([['name' => 'mohamed'], ['name' => 'ilies']]);

    expect($r->addCss($css)->setTemplate($template)->addTable($table)->get())
        ->toBeString()->toContain('ilies', 'mohamed');
})->with([
    'basic' => new RowBloom,
    'twig' => (new RowBloom)->setInterpolator('twig'),
    'html' => (new RowBloom)->setRenderer('html'),
    'HtmlRenderer instance' => (new RowBloom)->setRenderer(new HtmlRenderer),
]);
