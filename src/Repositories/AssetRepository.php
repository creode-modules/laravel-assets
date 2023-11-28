<?php

namespace Creode\LaravelAssets\Repositories;

use Creode\LaravelAssets\Models\Asset;
use Illuminate\Database\Eloquent\Collection;

class AssetRepository
{
    /**
     * Gets a collection of all assets.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return ($this->getAssetClass())::all();
    }

    /**
     * Gets a single asset by ID.
     *
     * @param integer $id
     * @return Asset
     */
    public function getById(int $id): Asset
    {
        return ($this->getAssetClass())::find($id);
    }

    /**
     * Creates a single asset.
     *
     * @param array $data
     * @return Asset
     */
    public function create(array $data): Asset
    {
        return ($this->getAssetClass())::create($data);
    }

    /**
     * Updates a single asset.
     *
     * @param integer $id
     * @param array $data
     * @return boolean
     */
    public function update(int $id, array $data): bool
    {
        return ($this->getAssetClass())::find($id)->update($data);
    }

    /**
     * Deletes an asset.
     *
     * @param integer $id
     * @return integer
     */
    public function delete(int $id): int
    {
        return ($this->getAssetClass())::destroy($id);
    }

    /**
     * Gets the class to be used with the model.
     *
     * @return \Creode\LaravelAssets\Models\Asset
     */
    protected function getAssetClass()
    {
        $class = config('assets.asset_model');

        if (class_exists($class)) {
            return $class;
        }

        return Asset::class;
    }
}
