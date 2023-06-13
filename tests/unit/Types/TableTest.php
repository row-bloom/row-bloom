<?php

use ElaborateCode\RowBloom\Types\Table;

it('functions', function () {
    $data1 = [
        ['a' => 1],
        ['b' => 2],
    ];
    $data2 = [
        ['a' => 1],
        ['b' => 2],
    ];

    $t1 = new Table($data1);
    $t1->append(new Table($data2));

    expect($t1->toArray())->toEqual(array_merge($data1, $data2));
});
