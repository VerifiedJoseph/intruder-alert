<?php

use IntruderAlert\Config;
use IntruderAlert\App\Backend;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Exception\AppException;

require 'vendor/autoload.php';

Output::text('Starting intruder alert daemon...');

while (true) {
    try {
        $config = new Config();
        $config->setDir(__DIR__);
        $config->check();
        $config->checkCli(php_sapi_name());

        $app = new Backend($config);
        $app->run();
    } catch (AppException $err) {
        Output::text($err->getMessage());
    } catch (ConfigException $err) {
        Output::text($err->getMessage());
        exit(1);
    }

    sleep(600);
}
