<?php

namespace Creode\LaravelAssets\Contracts;

use Creode\LaravelAssets\Models\Asset;

interface ThumbnailGeneratorInterface
{
    /**
     * Generates a thumbnail url for an asset.
     *
     * @param Asset $asset
     * @param string $thumbnailPath
     *
     * @return ?string
     */
    public function generateThumbnailUrl(Asset $asset, ?string $thumbnailPath): ?string;

    /**
     * Gets the type of output this generator produces.
     *
     * @return string
     */
    public function getOutputType(): string;
}
