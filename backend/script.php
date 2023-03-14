<?php

require 'vendor/autoload.php';

Lookup::setCountryDB('db/GeoLite2-Country.mmdb');
Lookup::setAsnDB('db/GeoLite2-ASN.mmdb');

$app = new App();
$app->run();
