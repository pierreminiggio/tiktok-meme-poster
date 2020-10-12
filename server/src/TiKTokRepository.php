<?php

namespace App;

class TiKTokRepository
{

    private string $tiktoksFile;

    public function __construct(string $tiktoksFile)
    {
        $this->tiktoksFile = $tiktoksFile;
    }

    public function all(): string
    {
        return file_get_contents($this->tiktoksFile);
    }
}
