<?php

namespace App\Repositories;

use App\Models\Image;
use App\Repositories\Contracts\ImageInterface;

class ImageRepository extends BaseRepository implements ImageInterface
{
    public function __construct(Image $image)
    {
        parent::__construct($image);
    }
}
