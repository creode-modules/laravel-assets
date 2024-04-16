<?php

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

    /*
    |--------------------------------------------------------------------------
    | Image Mime Types
    |--------------------------------------------------------------------------
    |
    | A list of mime types that are considered to be images. This is used when
    | determining whether to render an asset as an image.
    |
    */

    'image_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/tif',
        'image/webp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Model
    |--------------------------------------------------------------------------
    |
    | The model class to be used when interacting with assets. The model must
    | extend the Creode\LaravelAssets\Models\Asset class.
    |
    */

    'asset_model' => \Creode\LaravelAssets\Models\Asset::class,

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Disk
    |--------------------------------------------------------------------------
    |
    | The Disk used to store thumbnails. This can be any disk that you have
    | configured within your filesystems config file.
    |
     */
    'thumbnail_disk' => env('ASSET_THUMBNAIL_DISK', 'public'),
];
