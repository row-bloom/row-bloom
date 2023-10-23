<?php

use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Fs\FsException;

it('represents PHP File')
    ->expect(app()->make(File::class, ['path' => __FILE__]))
    ->exists()->toBeTrue()
    ->isFile()->toBeTrue()
    ->isDir()->toBeFalse()
    ->isWritable()->toBeTrue()
    ->extension()->toBe('php');

it('represents directory')
    ->expect(app()->make(File::class, ['path' => __DIR__]))
    ->exists()->toBeTrue()
    ->isDir()->toBeTrue()
    ->isFile()->toBeFalse()
    ->isWritable()->toBeTrue()
    ->isReadable()->toBeTrue()
    ->extension()->toBe('');

it('throws PHP File is not dir')
    ->expect(fn () => app()->make(File::class, ['path' => __FILE__])->mustBeDir())
    ->throws(FsException::class);
