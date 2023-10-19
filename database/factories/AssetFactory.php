<?php

namespace Creode\LaravelAssets\Database\Factories;

use Creode\LaravelAssets\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition()
    {
        return [
            'location' => $this->faker->image(null, 640, 480),
        ];
    }
}
