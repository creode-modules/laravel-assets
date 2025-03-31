<?php

namespace Creode\LaravelAssets\Commands;

use Creode\LaravelAssets\Repositories\AssetRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

final class GenerateAssetThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:generate-thumbnails {--ids=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds asset thumbnails.';

    /**
     * Constructor for class.
     */
    public function __construct(private AssetRepository $assetRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->withProgressBar($this->getAssets(), function ($asset) {
            // Trigger a save, which will regenerate thumbnails and reindex assets due to the `HasThumbnail` trait functionality.
            $asset->save();
        });
    }

    /**
     * Functionality for getting assets from the
     */
    private function getAssets(): Collection
    {
        $assets = $this->assetRepository;

        if ($this->option('ids')) {
            $assets = $assets->whereIn(
                'id',
                explode(',', $this->option('ids'))
            );
        }

        return $assets->get();
    }
}
