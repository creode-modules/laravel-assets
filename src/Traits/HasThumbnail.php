<?php

namespace Creode\LaravelAssets\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Creode\LaravelAssets\Jobs\RegenerateThumbnail;
use Creode\LaravelAssets\Support\ThumbnailGenerationService;

trait HasThumbnail
{
    public $shouldUpdateOnSave = true;

    /**
     * Fire off when model is initialised.
     */
    public static function bootHasThumbnail() {
        static::saving(function (Model $asset) {
            if (! $asset->shouldUpdateOnSave) {
                return;
            }

            RegenerateThumbnail::dispatch($asset);
        });

        static::deleting(function (Model $asset) {
            $asset->deleteThumbnail();
        });
    }

    /**
     * @deprecated 1.7.0 Use the `thumbnail` helper instead.
     *
     * Get the thumbnail url for the asset.
     */
    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $thumbnail = $this->thumbnail;

                return $thumbnail['url'] ?? null;
            }
        );
    }

    /**
     * Add thumbnail functionality to the asset.
     */
    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                return [
                    'url' => Storage::disk(config('assets.thumbnail_disk', 'public'))->url($this->thumbnail_path),
                    'type' => $this->thumbnail_type,
                ];
            }
        );
    }

    /**
     * Generates a filename for thumbnail.
     *
     * @return string
     */
    public function generateThumbnailFilename(): string {
        return uniqid() . '.jpg';
    }

    /**
     * Handles the deletion of an existing thumbnail.
     *
     * @return void
     */
    public function deleteThumbnail() {
        // If we don't already have a thumbnail bail out.
        if (!$this->thumbnail_path) {
            return;
        }

        // If the thumbnail provided doesn't exist bail out.
        if (! Storage::disk(config('assets.thumbnail_disk', 'public'))->exists($this->thumbnail_path)) {
            return;
        }

        Storage::disk(config('assets.thumbnail_disk', 'public'))->delete($this->thumbnail_path);
    }

    /**
     * Handle thumbnail generation.
     *
     * @return void
     */
    public function generateThumbnail() {
        // Generate thumbnail.
        $filename = $this->generateThumbnailFilename($this);

        // Generate.
        $generationService = app()->make(ThumbnailGenerationService::class);
        $thumbnail = $generationService->generateThumbnailForAsset($this, $filename);

        // Save path.
        $this->thumbnail_path = $filename;
        $this->thumbnail_type = $thumbnail['type'];
    }

    /**
     * Handles the saving of an Asset without regenerating it's thumbnail.
     *
     * @return void
     */
    public function saveWithoutGeneratingThumbnail() {
        $this->shouldUpdateOnSave = false;
        $this->save();
        $this->shouldUpdateOnSave = true;
    }
}
