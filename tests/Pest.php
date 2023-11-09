<?php

use Mockery\Mock;
use RowBloom\RowBloom\DataLoaders\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Support;
use RowBloom\RowBloom\Types\TableLocation;

uses()
    ->beforeEach(function () {
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

function defaultSupport(): Support
{
    return (new Support)->registerDataLoaderDriver(FolderDataLoader::NAME, FolderDataLoader::class)
        ->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class)
        ->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class)
        ->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);
}

function defaultRowBloom(): RowBloom
{
    return rowBloom(support: defaultSupport())->rowBloom;
}
