<?php

namespace Creode\LaravelAssets;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Creode\LaravelAssets\Support\ThumbnailGeneratorFactory;

class LaravelAssetsServiceProvider extends PackageServiceProvider
{
    /**
     * Registers classes into the service container.
     */
    public function register() {
        parent::register();

        $this->app->singleton('assets.thumbnail.factory', function() {
            return new ThumbnailGeneratorFactory();
        });
    }

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
            ->hasMigrations([
                'create_assets_table',
                'add_disk_field',
                'remove_disk_field',
            ])
            ->runsMigrations();
    }
}
