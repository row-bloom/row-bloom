<?php

use Illuminate\Container\Container;
use Mockery\Mock;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomServiceProvider;
use RowBloom\RowBloom\Types\TableLocation;

$sp = new RowBloomServiceProvider(Container::getInstance());
$sp->register();
$sp->boot();

uses()
    ->beforeEach(function () use ($sp) {
        // Container::getInstance()->forgetInstances();

        $sp->register();
        $sp->boot();

        Mockery::close();
    })
    ->beforeAll(function () {
        $folderPath = __DIR__.'/temp';
        if (! is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    })
    ->afterAll(function () {
        $folderPath = __DIR__.'/temp';
        deleteFolder($folderPath);
    })
    ->in('feature');

function deleteFolder($folderPath)
{
    if (! is_dir($folderPath)) {
        return;
    }

    $files = array_diff(scandir($folderPath), ['.', '..']);

    foreach ($files as $file) {
        $filePath = $folderPath.'/'.$file;

        if (is_dir($filePath)) {
            deleteFolder($filePath);
        } else {
            unlink($filePath);
        }
    }

    rmdir($folderPath);
}

function mockJsonFile(): File|Mock
{
    /** @var File|Mock */
    $file = Mockery::mock(File::class);

    $file->shouldReceive('exists')->andReturns(true);
    $file->shouldReceive('isDir')->andReturns(false);
    $file->shouldReceive('extension')->andReturns('json');
    $file->shouldReceive('mustExist')->andReturns($file);
    $file->shouldReceive('mustNotExist')->andReturns($file);
    $file->shouldReceive('mustBeDir')->andReturns($file);
    $file->shouldReceive('mustBeFile')->andReturns($file);
    $file->shouldReceive('mustBeWritable')->andReturns($file);
    $file->shouldReceive('mustBeReadable')->andReturns($file);
    $file->shouldReceive('mustBeExtension')->andReturns($file);

    return $file;
}

function mockJsonTableLocation(): TableLocation|Mock
{
    /** @var TableLocation|Mock */
    $location = Mockery::mock(TableLocation::class);

    $location->shouldReceive('getFile')->andReturns(mockJsonFile());

    return $location;
}
