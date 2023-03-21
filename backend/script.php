<?php

use Helper\Output;
use Exception\ConfigException;
use Exception\AppException;

require 'vendor/autoload.php';

if (php_sapi_name() !== 'cli') {
	Output::text('Intruder Alert script must be run via the command-line.');
	die();
}

try {
	Config::check();
	Lookup::setNetworkDB(constant('GEO_IP_ASN'));
	Lookup::setCountryDB(constant('GEO_IP_COUNTRY'));

	$app = new App();
	$app->run();
} catch (ConfigException | AppException $err) {
    Output::text($err->getMessage());
}

