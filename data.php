<?php

use Exception\AppException;
use Helper\Json;

require 'backend/vendor/autoload.php';

$data = '';

try {
    Config::setDir(__DIR__ . DIRECTORY_SEPARATOR . 'backend');

    $app = new App();
    $data = $app->getJsonReport();

} catch (AppException $err) {
    $data = Json::encode([
        'error' => true,
        'message' => $err->getMessage()
    ]);
} finally {
    header('cache-control: no-cache');
    header('content-type: application/json');
    echo $data;
}
