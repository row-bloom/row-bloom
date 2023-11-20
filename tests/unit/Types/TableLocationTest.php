<?php

use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\TableLocation;

test('valid URL/path', function (string $url, string $scheme, string $path) {
    $tableLocation = TableLocation::make($url);

    expect($tableLocation->scheme)->toBe($scheme);
    expect($tableLocation->path)->toBe($path);
})->with([
    [
        'url' => __FILE__,
        'scheme' => 'file',
        'path' => __FILE__,
    ],
    [
        'url' => __DIR__,
        'scheme' => 'file',
        'path' => __DIR__,
    ],
    [
        'url' => 'http://example.com/',
        'scheme' => 'http',
        'path' => '/',
    ],
]);

test('invalid URL/path', function (string $url) {
    expect(fn () => TableLocation::make($url))->toThrow(RowBloomException::class);
})->with([
    ['invalid'],
    ['http://example.com'], // no path
    ['/path/to/file'], // not real
]);

it('isFileLocation', function () {
    expect(TableLocation::make(__FILE__)->isFileLocation())->toBeTrue();
    expect(TableLocation::make(__DIR__)->isFileLocation())->toBeTrue();
    expect(TableLocation::make('http://example.com/')->isFileLocation())->toBeFalse();
});

test('toFile throws exception when scheme is not file', function () {
    expect(fn () => TableLocation::make('http://example.com/')->toFile())
        ->toThrow(RowBloomException::class);
});

