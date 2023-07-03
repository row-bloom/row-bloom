<?php

require __DIR__.'./../src/Container.php';

uses()
    ->beforeEach(function () {
        app()->forgetInstances();
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
    ->in('feature', 'unit');

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
