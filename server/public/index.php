<?php

use App\ImageUploader;

require __DIR__ . '/../vendor/autoload.php';

switch ($_SERVER['REQUEST_URI']) {
    case '/upload':
        (new ImageUploader())->upload();
        break;
    default:
        break;
}
