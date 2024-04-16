<?php

namespace Creode\LaravelAssets\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Creode\LaravelAssets\Events\ThumbnailWasGenerated;

trait HasThumbnail {
    /**
     * @deprecated 1.7.0 Use the `thumbnail` helper instead.
     *
     * Get the thumbnail url for the asset.
     *
     * @return Attribute
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
     *
     * @return Attribute
     */
    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $factory = resolve('assets.thumbnail.factory');

                // Use the factory to obtain the correct ThumbnailGenerator for this asset
                $generator = $factory->getGenerator($this);

                // Create and return the thumbnail using the generator
                $thumbnailUrl = $generator->generateThumbnailUrl($this);
                if (! $thumbnailUrl) {
                    return null;
                }

                $event = new ThumbnailWasGenerated($thumbnailUrl, $this);
                event($event);

                return [
                    'url' => $thumbnailUrl,
                    'generator' => get_class($generator),
                    'type' => $generator->getOutputType(),
                ];
            }
        );
    }
}
