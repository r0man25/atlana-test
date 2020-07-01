<?php

namespace App;

use App\Services\ImageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Image
 * @package App
 */
class Image extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = ['id'];

    const TYPE_ORIGINAL = 'original';
    const TYPE_SQUARE = 'square';
    const TYPE_SMALL = 'small';
    const TYPE_ALL = 'all';

    public function registerMediaCollections()
    {
        $this->addMediaCollection(self::TYPE_ORIGINAL);
        $this->addMediaCollection(self::TYPE_SQUARE);
        $this->addMediaCollection(self::TYPE_SMALL);
        $this->addMediaCollection(self::TYPE_ALL);
    }

    /**
     * @return int[]
     */
    public static function imageTypes()
    {
        return [self::TYPE_ORIGINAL, self::TYPE_SQUARE, self::TYPE_SMALL, self::TYPE_ALL];
    }

    /**
     * @param $type
     * @param $base64
     * @return bool
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\InvalidBase64Data
     */
    public static function storeImage($type, $base64)
    {
        $image = self::create(['type' => $type]);
//        $image = new self();

        $prepared = (new ImageService($type, $base64))->prepareImage();

        if (! $prepared) {
            return false;
        }

        if (is_array($prepared)) {
            foreach ($prepared as $code) {
                $image->addImageToLibrary($code);
            }

            return true;
        }

        if (is_string($prepared)) {
            $image->addImageToLibrary($prepared);

            return true;
        }

        return false;
    }

    /**
     * @param $base64
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\InvalidBase64Data
     */
    public function addImageToLibrary($base64)
    {
        $this
            ->addMediaFromBase64($base64)
            ->usingFileName($this->fileName($base64))
            ->toMediaCollection($this->type);
    }

    /**
     * @param $base64
     * @return string
     */
    public function fileName($base64)
    {
        return Carbon::now()->timestamp . '.' . explode('/', (ImageManagerStatic::make($base64))->mime)[1];
    }
}
