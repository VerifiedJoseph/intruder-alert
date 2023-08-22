<?php

use Helper\Output;
use Exception\ConfigException;
use Exception\AppException;

require 'vendor/autoload.php';

try {
    $config = new Config();
    $config->setDir(__DIR__);
    $config->check();

    Lookup::setNetworkDB($config->getAsnDatabasePath());
    Lookup::setCountryDB($config->getCountryDatabasePath());

    $app = new App($config);
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}

