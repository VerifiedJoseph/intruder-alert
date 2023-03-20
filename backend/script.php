<?php

use Helper\Output;
use Exception\ConfigException;

require 'vendor/autoload.php';

if (php_sapi_name() !== 'cli') {
	Output::text('Intruder Alert script must be run via the command-line.');
	die();
}

try {
	Config::check();
	Lookup::setCountryDB('db/GeoLite2-Country.mmdb');
	Lookup::setNetworkDB('db/GeoLite2-ASN.mmdb');

	$app = new App();
	$app->run();
} catch (ConfigException $err) {
    Output::text($err->getMessage());
}

