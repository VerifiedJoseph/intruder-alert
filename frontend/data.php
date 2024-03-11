<?php

use IntruderAlert\Config;
use IntruderAlert\App\Frontend;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Helper\Json;

require 'backend/vendor/autoload.php';

$data = '';

try {
    $config = new Config();
    $config->setDir('backend/');
    $config->check();

    $app = new Frontend($config);
    $data = $app->getJsonReport();
} catch (AppException | ConfigException $err) {
    $data = Json::encode([
        'error' => true,
        'message' => $err->getMessage()
    ]);
} finally {
    header('cache-control: no-cache');
    header('content-type: application/json');
    echo $data;
}
