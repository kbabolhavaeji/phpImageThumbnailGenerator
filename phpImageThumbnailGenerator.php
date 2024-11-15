<?php

/**
 * PHP Image Thumbnail Generator
 * Generates thumbnails from images using the GD library.
 * 
 * @author Kiumars Babolhavaeji
 * @date 30 December 2023
 */
class ImageThumbnailGenerator
{
    protected GdImage $image;
    protected GdImage $thumbnail;
    protected string $imageMime;
    protected string $fileName;

    /**
     * Constructor
     *
     * @param string $relativePath Relative path to the image file.
     * @throws Exception
     */
    public function __construct(string $relativePath)
    {
        $imagePath = getcwd() . $relativePath;

        $this->ensureGdIsLoaded();
        $this->ensureFileExists($imagePath);
        $this->ensureFileIsImage($imagePath);

        $this->setFileNameFromPath($imagePath);
        $this->loadImage($imagePath);
    }

    /**
     * Generate a thumbnail image and save it to the specified path.
     *
     * @param string $thumbnailPath Path to save the generated thumbnail.
     * @param int $thumbnailWidth Width of the thumbnail.
     * @param int $thumbnailHeight Height of the thumbnail.
     * @param string $chmod Optional permissions for the thumbnail file.
     * @throws Exception
     */
    public function generateThumbnail(
        string $thumbnailPath,
        int $thumbnailWidth,
        int $thumbnailHeight,
        string $chmod = '0644'
    ): void {
        $savePath = getcwd() . $thumbnailPath;
        [$originalWidth, $originalHeight] = [imagesx($this->image), imagesy($this->image)];

        $this->thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        $scalingRatio = min($originalWidth / $thumbnailWidth, $originalHeight / $thumbnailHeight);
        $scaledWidth = (int) round($originalWidth / $scalingRatio);
        $scaledHeight = (int) round($originalHeight / $scalingRatio);

        $cropX = (int) (($scaledWidth - $thumbnailWidth) / 2);
        $cropY = (int) (($scaledHeight - $thumbnailHeight) / 2);

        imagecopyresampled(
            $this->thumbnail,
            $this->image,
            0,
            0,
            $cropX,
            $cropY,
            $thumbnailWidth,
            $thumbnailHeight,
            $scaledWidth,
            $scaledHeight
        );

        $this->saveThumbnail($savePath);
        chmod($savePath, octdec($chmod));
    }

    /**
     * Ensure the GD library is loaded.
     *
     * @throws Exception
     */
    protected function ensureGdIsLoaded(): void
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new Exception('GD library is not available. Please enable it on the server.');
        }
    }

    /**
     * Check if a file exists.
     *
     * @param string $filePath
     * @throws Exception
     */
    protected function ensureFileExists(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found at: $filePath");
        }
    }

    /**
     * Ensure the file is a valid image.
     *
     * @param string $filePath
     * @throws Exception
     */
    protected function ensureFileIsImage(string $filePath): void
    {
        $mimeType = mime_content_type($filePath);
        $validMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp'];

        if (!in_array($mimeType, $validMimeTypes, true)) {
            throw new Exception('The specified file is not a valid image.');
        }

        $this->imageMime = $mimeType;
    }

    /**
     * Extract and sanitize the file name from the file path.
     *
     * @param string $filePath
     */
    protected function setFileNameFromPath(string $filePath): void
    {
        $this->fileName = str_replace(['%', ' '], ['-', '-'], basename($filePath));
    }

    /**
     * Load an image into a GD resource based on its mime type.
     *
     * @param string $filePath
     * @throws Exception
     */
    protected function loadImage(string $filePath): void
    {
        $this->image = match ($this->imageMime) {
            'image/jpeg' => imagecreatefromjpeg($filePath),
            'image/gif'  => imagecreatefromgif($filePath),
            'image/png'  => imagecreatefrompng($filePath),
            default      => throw new Exception('Unsupported image type.')
        };
    }

    /**
     * Save the generated thumbnail to the specified path.
     *
     * @param string $savePath
     * @throws Exception
     */
    protected function saveThumbnail(string $savePath): void
    {
        $outputFunction = match ($this->imageMime) {
            'image/jpeg' => 'imagejpeg',
            'image/gif'  => 'imagegif',
            'image/png'  => 'imagepng',
            default      => null
        };

        if (!$outputFunction || !$outputFunction($this->thumbnail, $savePath . $this->fileName)) {
            throw new Exception('Failed to save the thumbnail image.');
        }
    }
}
