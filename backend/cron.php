<?php

use IntruderAlert\App;
use IntruderAlert\Config;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Exception\AppException;

require 'vendor/autoload.php';
require 'include/version.php';

Output::text('Starting intruder alert cron task...');

try {
    $config = new Config();
    $config->check();
    $config->checkCli();

    $app = new App($config);
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}
