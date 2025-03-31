<?php

namespace Creode\LaravelAssets\Jobs;

use Creode\LaravelAssets\Models\Asset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RegenerateThumbnail implements ShouldQueue
{
    use Queueable;

    /**
     * Constructor for class.
     */
    public function __construct(
        public Asset $asset
    ) {}

    /**
     * Handles thumbnail generation.
     *
     * @return void
     */
    public function handle()
    {
        $this->asset->deleteThumbnail();
        $this->asset->generateThumbnail();
        $this->asset->saveWithoutGeneratingThumbnail();
    }
}
