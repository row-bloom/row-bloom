<?php

namespace ElaborateCode\RowBloom;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ElaborateCode\RowBloom\Commands\RowBloomCommand;

class RowBloomServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('row-bloom')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_row-bloom_table')
            ->hasCommand(RowBloomCommand::class);
    }
}
