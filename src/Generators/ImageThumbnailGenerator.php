<?php

namespace Creode\LaravelAssets\Generators;

use Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface;
use Creode\LaravelAssets\Models\Asset;
use Illuminate\Support\Facades\Storage;

class ImageThumbnailGenerator implements ThumbnailGeneratorInterface
{
    /**
     * The filename of the thumbnail.
     *
     * @var ?string
     */
    protected $filename = null;

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
    public function generateThumbnailUrl(Asset $asset): ?string
    {
        // Check if the asset exists.
        if (! Storage::disk(config('assets.disk', 'public'))->exists($asset->path)) {
            return null;
        }

        // Get the filename.
        $filename = $this->getFilename();

        // Copy the asset to the thumbnail disk.
        $assetContent = Storage::disk(config('assets.disk', 'public'))->get($asset->path);
        Storage::disk(config('assets.thumbnail_disk', 'public'))->put($filename, $assetContent);

        return Storage::disk(config('assets.thumbnail_disk', 'public'))->url($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename(): ?string
    {
        if (! $this->filename) {
            $this->generateFilename();
        }

        return $this->filename;
    }

    /**
     * Generates a filename.
     */
    protected function generateFilename(): void
    {
        $this->filename = uniqid().'.jpg';
    }
}
