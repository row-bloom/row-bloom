<?php

use ElaborateCode\RowBloom\RowBloom;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

it('renders', function () {
    $rendering = (new RowBloom)
        ->addCss(new Css(''))
        ->setTemplate(new Template('<h1>hey {{ name }}</h1>'))
        ->addTable(Table::fromArray([
            ['name' => 'mohamed'],
            ['name' => 'ilies'],
        ]))
        ->render();

    expect($rendering)->toBeString()
        ->toContain('ilies', 'mohamed');
});
