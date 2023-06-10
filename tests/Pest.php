<?php

uses()
    ->beforeAll(function () {
        $folderPath = __DIR__ . '/temp';
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    })
    ->afterAll(function () {
        $folderPath = __DIR__ . '/temp';
        deleteFolder($folderPath);
    })
    ->in('feature', 'unit');

function deleteFolder($folderPath)
{
    if (is_dir($folderPath)) {
        $files = array_diff(scandir($folderPath), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $folderPath . '/' . $file;

            if (is_dir($filePath)) {
                deleteFolder($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($folderPath);
        echo "Folder deleted!";
    } else {
        echo "Folder does not exist!";
    }
}
