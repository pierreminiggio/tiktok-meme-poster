<?php

namespace App;

use Intervention\Image\ImageManager;

class ImageUploader
{
    public function upload(?array $image): string
    {
        if (! $image) {
            return json_encode([
                'error' => 'Pas d\'image'
            ]);
        }
        if ($image['error']) {
            return json_encode([
                'error' => $image['error']
            ]);
        }

        $newImagePath = $this->generateNewImage($image['tmp_name']);

        $config = json_decode(file_get_contents(
            __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'config.json'
        ), true);

        $videoPath = (new VideoRenderer($config['vegasPath']))->render($newImagePath);

        return json_encode(['video' => $videoPath]);
    }

    private function generateNewImage(string $uploadedImage, int $i = 1): string
    {
        $imageFolder = __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'public'
            . DIRECTORY_SEPARATOR
            . 'images'
            . DIRECTORY_SEPARATOR
        ;
        $originalImagePath = $imageFolder . $i . '_original.png';
        $memeImagePath = $imageFolder . $i . '_sized.png';
        if (! file_exists($originalImagePath)) {
            copy($uploadedImage, $originalImagePath);
            $manager = new ImageManager(['driver' => 'imagick']);
            $image = $manager->make($originalImagePath);

            $originalWidth = $image->width();
            $originalHeight = $image->height();
            if ($originalWidth > $originalHeight) {
                $width = 1080;
                $height = (int) ((1080 / $originalWidth) * $originalHeight);
            } else {
                $height = 1920;
                $width = (int) ((1920 / $originalHeight) * $originalWidth);
            }
            $image->resize($width, $height);
            $image->resizeCanvas(1080, 1920);
            $image->save($memeImagePath, 80, 'png');
            
            return $memeImagePath;
        }

        return $this->generateNewImage($uploadedImage, $i + 1);
    }
}
