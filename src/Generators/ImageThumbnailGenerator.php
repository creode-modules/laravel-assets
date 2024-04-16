<?php

namespace Creode\LaravelAssets\Generators;

use Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface;
use Creode\LaravelAssets\Models\Asset;

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
    public function generateThumbnailUrl(Asset $asset): string
    {
        return $asset->url;
    }
}
