<?php

use IntruderAlert\App;
use IntruderAlert\Config;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Helper\Json;

require 'backend/vendor/autoload.php';

$data = '';

try {
    $config = new Config();
    $config->setDir(__DIR__ . DIRECTORY_SEPARATOR . 'backend');

    $app = new App($config);
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
