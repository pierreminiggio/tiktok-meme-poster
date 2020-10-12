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
        var_dump($image);
        echo 'test upload';
    }
}