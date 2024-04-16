<?php

namespace Creode\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Creode\LaravelAssets\Events\ThumbnailWasGenerated;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    /**
     * Sets the table for all models extending this model.
     *
     * @var string
     */
    protected $table = 'assets';

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($asset) {
            if (Storage::disk(config('assets.disk', 'public'))->exists($asset->location)) {
                Storage::disk(config('assets.disk', 'public'))->delete($asset->location);
            }
        });
    }

    /**
     * Gets the path to the asset.
     */
    public function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::disk(config('assets.disk', 'public'))
                ->path($attributes['location']),
        );
    }

    /**
     * Gets the assets url.
     */
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::disk(config('assets.disk', 'public'))
                ->url($attributes['location']),
        );
    }

    /**
     * Get the thumbnail url for the asset.
     *
     * @return Attribute
     */
    public function thumbnailUrl(): Attribute {
        return Attribute::make(
            get: function(mixed $value) {
                $factory = resolve('assets.thumbnail.factory');

                // Use the factory to obtain the correct ThumbnailGenerator for this asset
                $generator = $factory->getGenerator($this);

                // Create and return the thumbnail using the generator
                $thumbnailUrl = $generator->generateThumbnailUrl($this);
                if (!$thumbnailUrl) {
                    return null;
                }

                $event = new ThumbnailWasGenerated($thumbnailUrl, $this);
                event($event);

                return $event->thumbnailUrl;
            }
        );
    }

    /**
     * Determines if the asset is an image.
     */
    public function isImage(): bool
    {
        return in_array($this->mime_type, config('assets.image_mime_types'));
    }
}
