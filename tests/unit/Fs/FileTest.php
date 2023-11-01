<?php

use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Fs\FsException;

it('represents PHP File')
    ->expect(File::fromPath(__FILE__))
    ->exists()->toBeTrue()
    ->isFile()->toBeTrue()
    ->isDir()->toBeFalse()
    ->isWritable()->toBeTrue()
    ->extension()->toBe('php');

it('represents directory')
    ->expect(File::fromPath(__DIR__))
    ->exists()->toBeTrue()
    ->isDir()->toBeTrue()
    ->isFile()->toBeFalse()
    ->isWritable()->toBeTrue()
    ->isReadable()->toBeTrue()
    ->extension()->toBe('');

it('throws PHP File is not dir')
    ->expect(fn () => File::fromPath(__FILE__)->mustBeDir())
    ->throws(FsException::class);
