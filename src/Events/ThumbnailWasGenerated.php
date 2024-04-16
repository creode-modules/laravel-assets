<?php

namespace Creode\LaravelAssets\Events;

class ThumbnailWasGenerated
{
    /**
     * Create a new event instance.
     *
     * @param string $thumbnailUrl
     * @param \Creode\LaravelAssets\Models\Asset $asset
     */
    public function __construct(public $thumbnailUrl, public $asset)
    {
    }
}
