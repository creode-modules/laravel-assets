# Base module which is used for the DAM to create assets.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/creode/laravel-assets.svg?style=flat-square)](https://packagist.org/packages/creode/laravel-assets)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/creode-modules/laravel-assets/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/creode-modules/laravel-assets/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/creode-modules/laravel-assets/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/creode-modules/laravel-assets/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/creode/laravel-assets.svg?style=flat-square)](https://packagist.org/packages/creode/laravel-assets)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require creode/laravel-assets
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="assets-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="assets-config"
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Disk
    |--------------------------------------------------------------------------
    |
    | This value is the name of the disk where assets will be stored. This can
    | be any disk that you have configured in your filesystems.php config file.
    |
    */

    'disk' => env('FILESYSTEM_DISK', 'public'),
];
```

## Usage

```php
$asset = new Creode\LaravelAssets\Models\Asset;

// You can get the asset location from disk using a path.
$asset->path;

// You can get the url property of the asset.
$asset->url;
```

### Custom Thumbnail Generation
The ThumbnailGenerator allows for the registration of custom thumbnail generators to handle different asset types. This extensibility ensures that your application can easily adapt to new file types or specific thumbnail generation requirements.

#### Example

Here's a complete example of registering and using a custom thumbnail generator:

```php
// CustomThumbnailGenerator.php

namespace App\ThumbnailGenerators;
use App\Contracts\ThumbnailGeneratorInterface;
use App\Models\Asset;

class CustomThumbnailGenerator implements ThumbnailGeneratorInterface
{
    public function createThumbnail(Asset $asset, $dimensions): string
    {
        // Implement custom thumbnail generation logic
    }
}
```

```php
// AppServiceProvider.php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Support\ThumbnailGeneratorFactory;
use App\ThumbnailGenerators\CustomThumbnailGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Resolve the factory from the container
        $factory = $this->app->make('assets.thumbnail.factory');

        // Register your custom thumbnail generator
        $factory->extend('mime_type', function() {
            return new CustomThumbnailGenerator();
        });
    }
}
```

By integrating the custom generator as shown above, your application will now respond to the needs of different asset types and thumbnail generation strategies, while maintaining a clean and modular architecture.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Creode](https://github.com/creode)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
