<?php

require 'vendor/autoload.php';

require 'include/App.php';
require 'include/Lists/Ip.php';
require 'include/Lists/Network.php';
require 'include/Lists/Date.php';
require 'include/Lists/Jail.php';
require 'include/Lists/Country.php';
require 'include/Ip.php';
require 'include/Lists.php';
require 'include/Logs.php';
require 'include/Lookup.php';
require 'include/Report.php';

Lookup::setCountryDB('db/GeoLite2-Country.mmdb');
Lookup::setAsnDB('db/GeoLite2-ASN.mmdb');

$app = new App();
$app->run();
