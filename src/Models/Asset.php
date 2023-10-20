<?php

namespace Creode\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    use HasFactory;

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::disk(config('assets.disk'))
                ->path($attributes['location']),
        );
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::disk(config('assets.disk'))
                ->url($attributes['location']),
        );
    }
}
