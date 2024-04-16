<?php

namespace Creode\LaravelAssets\Support;

use Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface;
use Creode\LaravelAssets\Generators\ImageThumbnailGenerator;
use Creode\LaravelAssets\Generators\PDFThumbnailGenerator;
use Creode\LaravelAssets\Models\Asset;

class ThumbnailGeneratorFactory
{
    /**
     * List of custom generators that can be attached to the class.
     *
     * @var array
     */
    protected $customGenerators = [];

    /**
     * Constructor for class.
     */
    public function __construct(protected ?ThumbnailGeneratorInterface $defaultGenerator = null)
    {
    }

    /**
     * Add a custom generator to the factory.
     *
     * @param  string  $mime_type  The mime type used for generation.
     * @param  callable  $generator
     * @return void
     */
    public function addGenerator($mimeType, $generator)
    {
        $this->customGenerators[$mimeType] = $generator;
    }

    /**
     * Get the thumbnail generator for a provided asset.
     */
    public function getGenerator(Asset $asset): ?ThumbnailGeneratorInterface
    {
        // Use image.
        if ($asset->isImage()) {
            return new ImageThumbnailGenerator();
        }

        // Check and process the mime type.
        switch ($asset->mime_type) {
            case 'application/pdf':
                return new PDFThumbnailGenerator();
        }

        // Check if a custom generator is registered and return it
        if (isset($this->customGenerators[$asset->mime_type])) {
            return call_user_func($this->customGenerators[$asset->mime_type]);
        }

        // Use the default generator if one is set.
        if (isset($this->defaultGenerator)) {
            return $this->defaultGenerator;
        }

        return null;
    }
}
