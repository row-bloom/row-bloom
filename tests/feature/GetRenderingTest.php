<?php

use ElaborateCode\RowBloom\Interpolators\Interpolator;
use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
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
    'twig' => (new RowBloom)->setInterpolator(Interpolator::Twig),
    'html' => (new RowBloom)->setRenderer(Renderer::Html),
    'HtmlRenderer instance' => (new RowBloom)->setRenderer(new HtmlRenderer),
]);
