<?php

use Helper\Output;
use Exception\ConfigException;
use Exception\AppException;

require 'vendor/autoload.php';

try {
    Config::setDir(__DIR__);
    Config::check();

    Lookup::setNetworkDB(Config::getAsnDatabasePath());
    Lookup::setCountryDB(Config::getCountryDatabasePath());

    $app = new App();
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}

