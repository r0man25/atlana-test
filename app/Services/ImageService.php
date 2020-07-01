<?php

namespace App\Services;

use App\Image;
use Intervention\Image\Facades\Image as InterventionImage;

/**
 * Class ImageService
 * @package App\Services
 */
class ImageService
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $base64;

    /**
     * @var string
     */
    protected $backgroundSquare = '#ffffff';

    /**
     * ImageService constructor.
     * @param string $type
     * @param string $base64
     */
    public function __construct(string $type, string $base64)
    {
        $this->type = $type;
        $this->base64 = $base64;
    }

    /**
     * @return array|bool|string
     */
    public function prepareImage()
    {
        switch ($this->type) {
            case Image::TYPE_ORIGINAL:
                return $this->base64;
            case Image::TYPE_SQUARE:
                return $this->convertToSquare();
            case Image::TYPE_SMALL:
                return $this->convertToSmall();
            case Image::TYPE_ALL:
                return [
                    Image::TYPE_ORIGINAL => $this->base64,
                    Image::TYPE_SQUARE => $this->convertToSquare(),
                    Image::TYPE_SMALL => $this->convertToSmall(),
                ];
        }

        return false;
    }

    /**
     * @return string
     */
    public function convertToSquare()
    {
        $image = $this->makeImage();

        $width = $image->width();
        $height = $image->height();

        if ($width == $height) {
            return $this->base64;
        }

        $dimension = $width < $height ? $height : $width;
        $image->resizeCanvas($dimension, $dimension, 'center', false, $this->backgroundSquare);

        return $this->getBase64($image);
    }

    /**
     * @return string
     */
    public function convertToSmall()
    {
        $image = $this->makeImage();
        $image->resize(256, 256);

        return $this->getBase64($image);
    }

    /**
     * @return \Intervention\Image\Image
     */
    public function makeImage()
    {
        return InterventionImage::make($this->base64);
    }

    /**
     * @param \Intervention\Image\Image $image
     * @return string
     */
    public function getBase64(\Intervention\Image\Image $image)
    {
        return (string) $image->encode('data-url');
    }
}
