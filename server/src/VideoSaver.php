<?php

namespace App;

class VideoSaver
{
    public function save(?string $video, ?string $legend): string
    {
        if (! $video || ! $legend) {
            return json_encode(['error' => 'Missing video or legend']);
        }

        $tiktoksStorage = __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'tiktoks.json'
        ;

        $tiktoks = json_decode(file_get_contents($tiktoksStorage), true);
        $tiktoks[] = ['video' => $video, 'legend' => $legend];
        file_put_contents($tiktoksStorage, json_encode($tiktoks));

        return '';
    }
}
