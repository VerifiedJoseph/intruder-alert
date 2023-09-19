<?php

use IntruderAlert\App;
use IntruderAlert\Config;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ConfigException;
use IntruderAlert\Exception\AppException;

require 'vendor/autoload.php';
require 'include/version.php';

Output::text('Starting intruder alert daemon...');

while (true) {
    try {
        $config = new Config();
        $config->setDir(__DIR__);
        $config->checkCli();
        $config->check();

        $app = new App($config);
        $app->run();
    } catch (AppException $err) {
        Output::text($err->getMessage());
    } catch (ConfigException $err) {
        Output::text($err->getMessage());
        exit(1);
    }

    sleep(600);
}
