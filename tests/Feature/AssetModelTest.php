<?php

it('can create a new asset model', function () {
    $fileName = 'test.jpg';

    $asset = \Creode\LaravelAssets\Models\Asset::factory()->create([
        'location' => $fileName,
    ]);

    expect($asset->location)->toEqual($fileName);
});

it('can get a path from a different filesystem', function () {
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

    expect($asset->path)->toEqual("$filesystemPath/$fileName");
});

it('can get a url from a different filesystem', function () {
    // Create a custom filesystem.
    $filesystemUrl = 'http://testing/storage/';
    \Illuminate\Support\Facades\Config::set('filesystems.disks.testing', [
        'driver' => 'local',
        'root' => storage_path('app/testing'),
        'url' => $filesystemUrl,
        'visibility' => 'testing',
        'throw' => false,
    ]);

    // Set the asset disk to use the custom filesystem.
    \Illuminate\Support\Facades\Config::set('assets.disk', 'testing');

    $fileName = 'test.jpg';
    $asset = \Creode\LaravelAssets\Models\Asset::factory()->create([
        'location' => $fileName,
    ]);

    expect($asset->url)->toEqual($filesystemUrl.$fileName);
});
