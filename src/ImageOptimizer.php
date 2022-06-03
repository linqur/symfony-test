<?php

namespace App;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
    private const MAX_WIDTH = 200;
    private const MAX_HEIGHT = 150;

    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resize($filePath): void
    {
        list($iWidth, $iHeight) = getimagesize($filePath);

        $ratio = $iWidth / $iHeight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;

        if ($height / $width > $ratio) {
            $iWidth = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filePath);
        $photo->resize(new Box($width, $height))->save($filePath);
    }
}