<?php

namespace Creode\LaravelAssets\Contracts;

use Creode\LaravelAssets\Models\Asset;

interface ThumbnailGeneratorInterface
{
    /**
     * Generates a thumbnail url for an asset.
     */
    public function generateThumbnailUrl(Asset $asset): ?string;

    /**
     * Gets the type of output this generator produces.
     */
    public function getOutputType(): string;
}
