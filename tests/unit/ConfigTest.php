<?php

use RowBloom\RowBloom\Config;

test('setDriverConfig', function () {
    $config = new Config;
    $driverConfig = new stdClass;

    $config->setDriverConfig($driverConfig);

    expect($driverConfig)->toBe($config->getDriverConfig(stdClass::class));
});

test('getDriverConfig', function () {
    $config = new Config;
    $driverConfig = new stdClass;

    $config->setDriverConfig($driverConfig);

    expect($driverConfig)->toBe($config->getDriverConfig(stdClass::class));
});

test('tapDriverConfig', function () {
    $config = new Config;
    $driverConfig = new stdClass;

    $config->setDriverConfig($driverConfig);

    $config->tapDriverConfig(stdClass::class, fn (stdClass $config) => $config->property = 'value')
        ->tapDriverConfig(stdClass::class, fn (stdClass $config) => $config->property = 'value2');

    expect($config->getDriverConfig(stdClass::class)->property)->toBe('value2');
});
