<?php

namespace Creode\LaravelAssets\Generators;

use Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface;
use Creode\LaravelAssets\Models\Asset;
use Illuminate\Support\Facades\Storage;

class ImageThumbnailGenerator implements ThumbnailGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOutputType(): string
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function generateThumbnailUrl(Asset $asset, ?string $thumbnailPath): ?string
    {
        // Check if the asset exists.
        if (! Storage::disk(config('assets.disk', 'public'))->exists($asset->path)) {
            return null;
        }

        // Copy the asset to the thumbnail disk.
        $assetContent = Storage::disk(config('assets.disk', 'public'))->get($asset->path);
        Storage::disk(config('assets.thumbnail_disk', 'public'))->put($thumbnailPath, $assetContent);

        return Storage::disk(config('assets.thumbnail_disk', 'public'))->url($thumbnailPath);
    }
}
