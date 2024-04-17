<?php

namespace Creode\LaravelAssets\Events;

use Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface;

class ThumbnailWasGenerated
{
    /**
     * Create a new event instance.
     *
     * @param  string  $thumbnailUrl
     * @param  \Creode\LaravelAssets\Models\Asset  $asset
     */
    public function __construct(protected ThumbnailGeneratorInterface $generator, public $thumbnailUrl, public $asset)
    {
    }
}
