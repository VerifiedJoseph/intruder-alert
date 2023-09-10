<?php

use IntruderAlert\App;
use IntruderAlert\Config;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Exception\AppException;

require 'vendor/autoload.php';

try {
    $config = new Config();
    $config->setDir(__DIR__);
    $config->check();

    $app = new App($config);
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}

