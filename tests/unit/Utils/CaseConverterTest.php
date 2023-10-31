<?php

use RowBloom\RowBloom\Utils\CaseConverter;

test('snakeToCamel', function (string $from, string $to) {
    expect(CaseConverter::snakeToCamel($from))->toBe($to);
})->with([
    ['', ''],
    ['_', ''],
    ['_____', ''],
    ['hello', 'hello'],
    ['world', 'world'],
    ['hello_world', 'helloWorld'],
    ['snake_case_string', 'snakeCaseString'],
    ['hello1', 'hello1'],
    ['snake123_case', 'snake123Case'],
    ['hello_world!', 'helloWorld!'],
    ['snake_case_@string', 'snakeCase@string'],
    ['_hello_world_', 'helloWorld'],
    ['__hello__world__', 'helloWorld'],
    ['hello_world__', 'helloWorld'],
]);
