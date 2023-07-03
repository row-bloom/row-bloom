<?php

use ElaborateCode\RowBloom\Fs\File;

it('mock', function () {
    $mock = Mockery::mock(File::class);

    $mock->shouldReceive('readFileContent')->andReturns('foo');

    expect($mock->readFileContent())->toBe('foo');
});

it('mock in container', function () {
    $mock = Mockery::mock(File::class);

    $mock->shouldReceive('readFileContent')->andReturns('foo');

    app()->instance(File::class, $mock);

    expect(app()->make(File::class)->readFileContent())->toBe('foo');
});

it('mock in container (bounded)', function () {
    $mock = Mockery::mock(File::class);

    $mock->shouldReceive('readFileContent')->andReturns('foo');

    app()->instance(File::class, $mock);

    expect(app()->make(File::class, ['path' => ''])->readFileContent())->toBe('foo');
})->todo();
