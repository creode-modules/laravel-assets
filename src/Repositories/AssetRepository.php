<?php

namespace Creode\LaravelAssets\Repositories;

use Creode\LaravelAssets\Models\Asset;
use Creode\LaravelRepository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class AssetRepository extends BaseRepository
{
    /**
     * Gets a collection of all assets.
     */
    public function getAll(): Collection
    {
        return ($this->getModel())::all();
    }

    /**
     * Gets a single asset by ID.
     */
    public function getById(int $id): Asset
    {
        return ($this->getModel())::find($id);
    }

    /**
     * Creates a single asset.
     */
    public function create(array $data): Asset
    {
        return ($this->getModel())::create($data);
    }

    /**
     * Updates a single asset.
     */
    public function update(int $id, array $data): bool
    {
        return ($this->getModel())::find($id)->update($data);
    }

    /**
     * Deletes an asset.
     */
    public function delete(int $id): int
    {
        return ($this->getModel())::destroy($id);
    }

    /**
     * Gets the class to be used with the model.
     *
     * @return \Creode\LaravelAssets\Models\Asset
     */
    protected function getModel(): string
    {
        $class = config('assets.asset_model');

        if (class_exists($class)) {
            return $class;
        }

        return Asset::class;
    }
}
