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

        $newImagePath = $this->generateNewImage();

        copy($image['tmp_name'], $newImagePath);
    }

    private function generateNewImage(int $i = 1): string
    {
        $newImagePath = __DIR__ . '/../public/images/' . $i . '_original.png';
        if (! file_exists($newImagePath)) {
            return $newImagePath;
        }

        return $this->generateNewImage($i + 1);
    }
}
