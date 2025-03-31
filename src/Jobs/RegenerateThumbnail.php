<?php

namespace Creode\LaravelAssets\Jobs;

use Creode\LaravelAssets\Models\Asset;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegenerateThumbnail implements ShouldQueue
{
    use Queueable;

    /**
     * Constructor for class.
     *
     * @param Asset $asset
     */
    public function __construct(
        public Asset $asset
    ) {}

    /**
     * Handles thumbnail generation.
     *
     * @return void
     */
    public function handle() {
        $this->asset->deleteThumbnail();
        $this->asset->generateThumbnail();
        $this->asset->saveWithoutGeneratingThumbnail();
    }
}
