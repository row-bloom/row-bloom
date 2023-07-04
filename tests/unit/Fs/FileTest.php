<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Fs\FsException;

it('represents paths', function () {
    $file = app()->make(File::class, ['path' => __FILE__]);

    expect($file->exists())->toBeTrue();
    expect($file->isFile())->toBeTrue();
    expect($file->isDir())->toBeFalse();
    expect($file->isWritable())->toBeTrue();
    expect($file->extension())->toBe('php');
    expect(fn () => $file->mustBeDir())->toThrow(FsException::class);
});

test('edge cases', function () {
})->todo();
