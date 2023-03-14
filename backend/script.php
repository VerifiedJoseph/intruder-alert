<?php

require 'vendor/autoload.php';

require 'include/App.php';
require 'include/IpList.php';
require 'include/NetworkList.php';
require 'include/DateList.php';
require 'include/JailList.php';
require 'include/CountryList.php';
require 'include/Ip.php';
require 'include/Lists.php';
require 'include/Logs.php';
require 'include/Lookup.php';
require 'include/Report.php';

Lookup::setCountryDB('db/GeoLite2-Country.mmdb');
Lookup::setAsnDB('db/GeoLite2-ASN.mmdb');

$app = new App();
$app->run();
