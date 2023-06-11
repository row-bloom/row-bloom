<?php

use ElaborateCode\RowBloom\RowBloom;

it('renders', function () {
    $rendering = (new RowBloom)
        ->css('')
        ->template('<h1>hey {{ name }}</h1>')
        ->table([
            ['name' => 'mohamed'],
            ['name' => 'ilies'],
        ])
        ->render();

    expect($rendering)->toBeString();
    expect($rendering)->toContain('ilies');
    expect($rendering)->toContain('mohamed');
});
