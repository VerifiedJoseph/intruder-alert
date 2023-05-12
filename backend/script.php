<?php

use Helper\Output;
use Exception\ConfigException;
use Exception\AppException;

require 'vendor/autoload.php';

try {
    Config::check();
    Lookup::setNetworkDB(constant('ASN_DATABASE'));
    Lookup::setCountryDB(constant('COUNTRY_DATABASE'));

    $app = new App();
    $app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}

