<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a new asset model', function () {
    $fileName = 'test.jpg';

    $asset = \Creode\LaravelAssets\Models\Asset::factory()->create([
        'location' => $fileName,
    ]);

    expect($asset->location)->toEqual($fileName);
});

it('can get a path from the asset filesystem', function () {
    // Create a custom filesystem.
    $filesystemPath = storage_path('app/testing');
    \Illuminate\Support\Facades\Config::set('filesystems.disks.testing', [
        'driver' => 'local',
        'root' => $filesystemPath,
        'url' => 'http://testing/storage/',
        'visibility' => 'testing',
        'throw' => false,
    ]);

    // Set the asset disk to use the custom filesystem.
    \Illuminate\Support\Facades\Config::set('assets.disk', 'testing');

    $fileName = 'test.jpg';
    $asset = \Creode\LaravelAssets\Models\Asset::factory()->create([
        'location' => $fileName,
    ]);

    expect($asset->path)->toEqual($filesystemPath.DIRECTORY_SEPARATOR.$fileName);
});

it('can get a url of a file', function () {
    // Storage::fake('testing');

    $diskName = 'testing';

    // Set the asset disk to use the custom filesystem.
    \Illuminate\Support\Facades\Config::set('assets.disk', $diskName);

    // Create a custom filesystem.
    $filesystemUrl = 'http://testing/storage/';
    \Illuminate\Support\Facades\Config::set('filesystems.disks.testing', [
        'driver' => 'local',
        'root' => storage_path('app/testing'),
        'url' => $filesystemUrl,
        'visibility' => 'public',
        'throw' => false,
    ]);

    $fileName = 'test.jpg';
    $asset = \Creode\LaravelAssets\Models\Asset::factory()->create([
        'location' => $fileName,
    ]);

    expect($asset->url)->toEqual($filesystemUrl.$fileName);
});

it('can delete an asset file when the model is deleted', function () {
    \Illuminate\Support\Facades\Storage::fake('testing');

    $diskName = 'testing';

    // Set the asset disk to use the custom filesystem.
    \Illuminate\Support\Facades\Config::set('assets.disk', $diskName);

    \Illuminate\Support\Facades\Storage::disk($diskName)->put('test.jpg', 'test.jpg');

    $fileName = 'test.jpg';
    $asset = \Creode\LaravelAssets\Models\Asset::factory()->create([
        'location' => $fileName,
    ]);

    $asset->delete();

    expect(\Illuminate\Support\Facades\Storage::disk($diskName)->exists($fileName))->toBeFalse();
});
