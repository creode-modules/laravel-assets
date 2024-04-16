<?php

namespace Creode\LaravelAssets\Generators;

use Creode\LaravelAssets\Models\Asset;
use Illuminate\Support\Facades\Storage;
use Creode\LaravelAssets\Contracts\ThumbnailGeneratorInterface;

class PDFThumbnailGenerator implements ThumbnailGeneratorInterface {
    /**
     * {@inheritdoc}
     */
    public function generateThumbnailUrl(Asset $asset): ?string {
        // We need to have imagick installed to use this generator.
        if (!extension_loaded('imagick')) {
            return null;
        }

        // Check if Ghostscript is installed.
        if (!$this->isGhostscriptInstalled()) {
            return null;
        }

        // Check if the asset exists.
        if (!Storage::disk(config('assets.disk', 'public'))->exists($asset->path)) {
            return null;
        }

        // Look for the file.
        $imageName = $this->generateThumbnailFileName($asset->url);

        // If we can't find the thumbnail, create it.
        if (!Storage::disk(config('assets.thumbnail_disk', 'public'))->exists($imageName)) {
            $this->createThumbnail($asset->url, $imageName);
        }

        return Storage::disk(config('assets.thumbnail_disk', 'public'))->url($imageName);
    }

    /**
     * Creates a thumbnail for a PDF.
     *
     * @param string $assetUrl
     * @param string $imageName
     */
    protected function createThumbnail($assetUrl, $imageName): void {
        try {
            // The file needs to be stored locally before Imagick can process it.
            $tempPdfPath = $this->downloadPdfToTemporaryPath($assetUrl);

            $im = new \Imagick();
            $im->readImage(Storage::path($tempPdfPath) . '[0]');
            $im->setResolution(300, 300);
            $im->setImageBackgroundColor('white');
            $im->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
            $im->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
            $im->setImageFormat('jpg');

            $this->deletePdfFromTemporaryPath($tempPdfPath);

            Storage::disk(config('assets.thumbnail_disk', 'public'))->put($imageName, $im->getImageBlob());
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate thumbnail for PDF: ' . $e->getMessage());
        }
    }

    /**
     * Downloads a PDF to a temporary path.
     *
     * @param string $assetUrl
     *
     * @return string
     */
    protected function downloadPdfToTemporaryPath(string $assetUrl): string {
        $tempPdfPath = 'assets/tmp/asset-download';
        $pdf = file_get_contents($assetUrl);
        Storage::put($tempPdfPath, $pdf);

        return $tempPdfPath;
    }

    /**
     * Removes the temporary pdf file from storage.
     *
     * @param string $tempPdfPath
     */
    protected function deletePdfFromTemporaryPath(string $tempPdfPath): void {
        Storage::delete($tempPdfPath);
    }

    /**
     * Generate a thumbnail file name.
     *
     * @param string $assetUrl
     *
     * @return string
     */
    protected function generateThumbnailFileName(string $assetUrl) {
        return md5(basename($assetUrl)). '.jpg';
    }

    /**
     * Check if Ghostscript is installed.
     *
     * @return bool
     */
    private function isGhostscriptInstalled(): bool {
        $gsVersionCommand = 'gs --version';

        exec($gsVersionCommand, $output, $returnVar);

        if ($returnVar === 0) {
            return true;
        }

        return false;
    }
}
