<?php

/*
	Script for extracting IP ban details from fail2ban log files and creating events.json
 */

include('../../backend/vendor/autoload.php');
include('../../backend/config.php');

use IntruderAlert\Config;
use IntruderAlert\Logger;
use IntruderAlert\Logs\Logs;

$config = new Config();
$config->check();
$config->checkCli((string) php_sapi_name());

$timezone = 'Europe/London';

$networkDatabase = new IntruderAlert\Database\Network($config->getAsnDatabasePath(), new Logger($timezone));
$countryDatabase = new IntruderAlert\Database\Country($config->getCountryDatabasePath(), new Logger($timezone));

$logs = new Logs($config, new Logger($timezone));
$events = $logs->process();

$number = 0;
$date = date('Y-m-d', strtotime($events[0]['timestamp']));

foreach ($events as &$event) {
	$currentDate = date('Y-m-d', strtotime($event['timestamp']));

	if ($currentDate !== $date) {
		$number++;
		$date = $currentDate;
	}

	$region = $countryDatabase->lookup($event['ip']);
	$network = $networkDatabase->lookup($event['ip']);

	$event['timestamp'] = str_replace($currentDate, $number, $event['timestamp']);
	$event['country'] = $region['country'];
	$event['continent'] = $region['continent'];
	$event['network'] = $network;
}

file_put_contents('../events.json', json_encode($events));