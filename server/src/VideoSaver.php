<?php

namespace App;

class VideoSaver
{

    private string $tiktoksFile;

    public function __construct(string $tiktoksFile)
    {
        $this->tiktoksFile = $tiktoksFile;
    }

    public function save(?string $video, ?string $legend): string
    {
        if (! $video || ! $legend) {
            return json_encode(['error' => 'Missing video or legend']);
        }

        $tiktoks = json_decode(file_get_contents($this->tiktoksFile), true);
        $tiktoks[] = ['video' => $video, 'legend' => $legend];
        file_put_contents($this->tiktoksFile, json_encode($tiktoks));

        return '';
    }
}
