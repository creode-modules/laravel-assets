<?php

namespace Creode\LaravelAssets\Models;

use Creode\LaravelAssets\Traits\HasThumbnail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    use HasFactory, HasThumbnail;

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
     * Determines if the asset is an image.
     */
    public function isImage(): bool
    {
        return in_array($this->mime_type, config('assets.image_mime_types'));
    }
}
