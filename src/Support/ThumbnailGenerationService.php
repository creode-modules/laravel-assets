<?php

namespace Creode\LaravelAssets\Support;

use Creode\LaravelAssets\Events\ThumbnailWasGenerated;
use Creode\LaravelAssets\Models\Asset;

class ThumbnailGenerationService
{
    /**
     * Handles the generation of a thumbnail for a specific asset.
     */
    public function generateThumbnailForAsset(Asset $asset, string $thumbnailFilename): ?array
    {
        // Use the factory to obtain the correct ThumbnailGenerator for this asset
        $factory = resolve('assets.thumbnail.factory');

        /** @var \Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface $generator */
        $generator = $factory->getGenerator($asset);
        if (! $generator) {
            return null;
        }

        // Create and return the thumbnail using the generator
        $thumbnailUrl = $generator->generateThumbnailUrl($asset, $thumbnailFilename);
        if (! $thumbnailUrl) {
            return null;
        }

        $event = new ThumbnailWasGenerated($generator, $thumbnailUrl, $asset);
        event($event);

        return [
            'url' => $event->thumbnailUrl,
            'generator' => get_class($generator),
            'type' => $generator->getOutputType(),
        ];
    }
}
