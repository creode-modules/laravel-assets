<?php

namespace Creode\LaravelAssets;

use Creode\LaravelAssets\Commands\LaravelAssetsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAssetsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-assets')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-assets_table')
            ->hasCommand(LaravelAssetsCommand::class);
    }
}
