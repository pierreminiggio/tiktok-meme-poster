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

        $newImagePath = $this->getNewImagePath();

        copy($image['tmp_name'], $newImagePath);
    }

    private function getNewImagePath(int $i = 1): string
    {
        $newImagePath = __DIR__ . '/../public/images/' . $i . '.png';
        if (! file_exists($newImagePath)) {
            return $newImagePath;
        }

        return $this->getNewImagePath($i + 1);
    }
}