<?php

namespace Creode\LaravelAssets\Repositories;

use Creode\LaravelAssets\Models\Asset;
use Creode\LaravelRepository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class AssetRepository extends BaseRepository
{
    /**
     * Gets a collection of all assets.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return ($this->getModel())::all();
    }

    /**
     * Gets a single asset by ID.
     *
     * @param integer $id
     * @return Asset
     */
    public function getById(int $id): Asset
    {
        return ($this->getModel())::find($id);
    }

    /**
     * Creates a single asset.
     *
     * @param array $data
     * @return Asset
     */
    public function create(array $data): Asset
    {
        return ($this->getModel())::create($data);
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
        return ($this->getModel())::find($id)->update($data);
    }

    /**
     * Deletes an asset.
     *
     * @param integer $id
     * @return integer
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
