<?php

namespace App;

use Intervention\Image\ImageManager;

class ImageUploader
{
    public function upload(?array $image)
    {
        if (! $image) {
            echo json_encode([
                'error' => 'Pas d\'image'
            ]);

            return;
        }
        if ($image['error']) {
            echo json_encode([
                'error' => $image['error']
            ]);

            return;
        }

        $newImagePath = $this->generateNewImage($image['tmp_name']);
    }

    private function generateNewImage(string $uploadedImage, int $i = 1): string
    {
        $originalImagePath = __DIR__ . '/../public/images/' . $i . '_original.png';
        $memeImagePath = __DIR__ . '/../public/images/' . $i . '_sized.png';
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
