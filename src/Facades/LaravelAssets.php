<?php

namespace Creode\LaravelAssets\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Creode\LaravelAssets\LaravelAssets
 */
class LaravelAssets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Creode\LaravelAssets\LaravelAssets::class;
    }
}
