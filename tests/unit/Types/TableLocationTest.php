<?php

use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\TableLocation;

test('valid URL/path', function (string $url, string $scheme, string $path) {
    $tableLocation = TableLocation::make($url);

    expect($tableLocation->scheme)->toBe($scheme);
    expect($tableLocation->path)->toBe($path);
})->with([
    ['url' => __FILE__,                  'scheme' => 'file', 'path' => __FILE__],
    ['url' => __DIR__,                   'scheme' => 'file', 'path' => __DIR__],
    ['url' => 'http://example.com/',     'scheme' => 'http', 'path' => '/'],
    ['url' => 'file://localhost',        'scheme' => 'file', 'path' => '/'],
    ['url' => 'file:///',                'scheme' => 'file', 'path' => '/'],
    ['url' => 'file:/',                  'scheme' => 'file', 'path' => '/'],
    ['url' => 'file:///a',               'scheme' => 'file', 'path' => '/a'],
    ['url' => 'file:///C:/WIN/foo.txt',  'scheme' => 'file', 'path' => 'C:/WIN/foo.txt'],
    ['url' => 'file:///C:/WIN',          'scheme' => 'file', 'path' => 'C:/WIN'],
    ['url' => 'file:/a',                 'scheme' => 'file', 'path' => '/a'], // Unix KDE
    ['url' => 'ftp://x/foo/../bar',      'scheme' => 'ftp',  'path' => '/foo/../bar'],
]);

test('invalid URL/path', function (string $url) {
    expect(fn () => TableLocation::make($url))->toThrow(RowBloomException::class);
})->with([
    ['invalid'],
    ['/path/to/file'], // not real
    // no path no authority
    ['http://'],
    ['ftp:'],
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
