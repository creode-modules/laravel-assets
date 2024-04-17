<?php

namespace Creode\LaravelAssets\Traits;

use Creode\LaravelAssets\Events\ThumbnailWasGenerated;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasThumbnail
{
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
                $factory = resolve('assets.thumbnail.factory');

                // Use the factory to obtain the correct ThumbnailGenerator for this asset
                $generator = $factory->getGenerator($this);
                if (! $generator) {
                    return null;
                }

                // Create and return the thumbnail using the generator
                $thumbnailUrl = $generator->generateThumbnailUrl($this);
                if (! $thumbnailUrl) {
                    return null;
                }

                $event = new ThumbnailWasGenerated($generator, $thumbnailUrl, $this);
                event($event);

                return [
                    'url' => $event->thumbnailUrl,
                    'generator' => get_class($generator),
                    'type' => $generator->getOutputType(),
                ];
            }
        );
    }
}
