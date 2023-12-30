<?php

/**
 * @author Kiumars Babolhavaeji - k.babolhavaeji@gmail.com
 * 30 December 2023
 */
class phpImageThumbnailGenerator
{

    protected GdImage $image;
    protected GdImage $thumbnail;

    protected $imageMime;
    protected $fileName;

    /**
     * @param string $realPath
     * @throws Exception
     *
     */
    public function __construct(
        string $realPath,
    )
    {

        $imagePath = getcwd() . $realPath;

        // check rather GD module is installed on the server or not
        $this->checkGD();

        // check the file to be exists
        $this->checkFileExistence($imagePath);

        // check if file is Image or not
        $this->checkIsImage($imagePath);

        // extract file name
        $this->setFileName($imagePath);

        // set up an instance from GD by using original image file
        $this->readFile($imagePath);

    }

    /**
     * @param string $thumbnailPath
     * @param int $thumbnailWidth
     * @param int $thumbnailHeight
     * @param string $chmod
     */
    public function generateThumbnail(string $thumbnailPath, int $thumbnailWidth, int $thumbnailHeight, string $chmod = '0644'): void
    {
        $savePath = getcwd() . $thumbnailPath;
        $width = imagesx($this->image);
        $height = imagesy($this->image);

        $this->thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        $widthRatio  = $width /  $thumbnailWidth;
        $heightRatio = $height / $thumbnailHeight;

        $optimalRatio = min($heightRatio, $widthRatio);

        $width  = round( $width  / $optimalRatio );
        $height = round( $height / $optimalRatio );

        $cropStartX = ($width / 2) - ($thumbnailWidth / 2);
        $cropStartY = ($height / 2) - ($thumbnailHeight / 2);

        imagecopyresampled($this->thumbnail, $this->image, 0, 0, $cropStartX, $cropStartY, $thumbnailWidth, $thumbnailHeight, $width, $height);

        match ($this->imageMime) {
            'image/jpeg' => imagejpeg($this->thumbnail, $savePath . $this->fileName),
            'image/gif'  => imagegif($this->thumbnail, $savePath . $this->fileName),
            'image/png'  => imagepng($this->thumbnail, $savePath . $this->fileName)
        };

        chmod($savePath, 0755);
    }

    /**
     * @throws Exception
     */
    protected function checkGD(): void
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new Exception('GD module is not loaded, please contact to the server supervisor.');
        }
    }

    /**
     * @throws Exception
     */
    protected function checkFileExistence($imageRealPath): void
    {
        if (!file_exists($imageRealPath)) {
            throw new Exception('Image does not exist');
        }
    }

    /**
     * @throws Exception
     */
    protected function checkIsImage($imageRealPath): void
    {

        $file = $imageRealPath;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file);
        finfo_close($finfo);

        $is_image_file = match ($mimeType) {
            'image/jpeg', 'image/gif', 'image/png', 'image/bmp' => true,
            default                                             => false,
        };

        if (!$is_image_file) {
            throw new Exception('file is not image.');
        }
    }

    /**
     * @param $realPath
     * @return void
     */
    protected function readFile($realPath): void
    {
        $dimension = getimagesize($realPath);
        $this->imageMime = $dimension['mime'];

        $this->image = match ($this->imageMime) {
            'image/jpeg' => imagecreatefromjpeg($realPath),
            'image/gif'  => imagecreatefromgif($realPath),
            'image/png'  => imagecreatefrompng($realPath),
            default      => null,
        };
    }

    /**
     * @param $path
     * @return void
     */
    protected function setFileName($path): void
    {
        $image = explode('/', $path);
        $name = end($image);
        $name = str_replace('%', '-', $name);
        $this->fileName = str_replace(' ', '-', $name);
    }

}