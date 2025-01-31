<?php

declare(strict_types=1);

use IntruderAlert\Config;
use IntruderAlert\App\Backend;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Exception\AppException;

require 'vendor/autoload.php';

Output::text('Starting intruder alert task...');

try {
    $config = new Config();
    $config->setDir(__DIR__);
    $config->check();
    $config->checkCli((string) php_sapi_name());

    $app = new Backend($config);
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
    exit(1);
}
