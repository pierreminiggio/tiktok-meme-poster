<?php

namespace App;

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

            return $originalImagePath;
        }

        return $this->generateNewImage($uploadedImage, $i + 1);
    }
}
