<?php

namespace Creode\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            Storage::disk($asset->disk)->delete($asset->location);
        });
    }

    /**
     * Gets the path to the asset.
     */
    public function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::disk($this->disk)
                ->path($attributes['location']),
        );
    }

    /**
     * Gets the assets url.
     */
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::disk($this->disk)
                ->url($attributes['location']),
        );
    }

    /**
     * Determines if the asset is an image.
     */
    public function isImage(string $mimeType): bool
    {
        return in_array($mimeType, config('assets.image_mime_types'));
    }
}
