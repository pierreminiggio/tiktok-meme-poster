<?php

header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\ImageUploader;

require __DIR__ . '/../vendor/autoload.php';

switch ($_SERVER['REQUEST_URI']) {
    case '/upload':
        (new ImageUploader())->upload(isset($_FILES['image']) ? $_FILES['image'] : null);
        break;
    default:
        break;
}
