<?php

namespace Creode\LaravelAssets\Commands;

use Illuminate\Console\Command;

class LaravelAssetsCommand extends Command
{
    public $signature = 'laravel-assets';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
