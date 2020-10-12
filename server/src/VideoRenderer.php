<?php

namespace App;

class VideoRenderer
{

    private string $vegasPath;

    public function __construct(string $vegasPath)
    {
        $this->vegasPath = $vegasPath;
    }

    public function render(string $imagePath): string
    {
        $splitedPaths = explode(DIRECTORY_SEPARATOR, $imagePath);

        $publicPath = '';

        foreach ($splitedPaths as $index => $splitedPath) {
            if ($index && $index !== count($splitedPaths) - 1) {
                $publicPath .= DIRECTORY_SEPARATOR;
            }
            if ($index < count($splitedPaths) - 2) {
                $publicPath .= $splitedPath;
            }
        }
        $imageName = $splitedPaths[count($splitedPaths) - 1];

        $fileBaseName = str_replace('_sized', '', explode('.', $imageName)[0]);
        $mp4File = $publicPath
            . 'videos'
            . DIRECTORY_SEPARATOR
            . $fileBaseName
            . '.mp4'
        ;
        file_put_contents(
            __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'tmp.csv',
            $imagePath
            . ';'
            . $publicPath
            . 'musiques'
            . DIRECTORY_SEPARATOR
            . 'coffin-dance-9.mp3;'
            . 7000
            . ';'
            . $publicPath
            . 'projets'
            . DIRECTORY_SEPARATOR
            . $fileBaseName
            . '.veg;'
            . $mp4File
        );

        exec(
            $this->vegasPath
            . ' -SCRIPT:"'
            . __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'RenderProject'
            . DIRECTORY_SEPARATOR
            . 'RenderProject'
            . DIRECTORY_SEPARATOR
            . 'class1.cs"'
        );

        return $mp4File;
    }
}
