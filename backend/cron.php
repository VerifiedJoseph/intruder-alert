<?php

use IntruderAlert\Config;
use IntruderAlert\App\Backend;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Exception\AppException;

require 'vendor/autoload.php';

Output::text('Starting intruder alert cron task...');

try {
    $config = new Config();
    $config->setDir(__DIR__);
    $config->check();
    $config->checkCli(php_sapi_name());

    $app = new Backend($config);
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}
