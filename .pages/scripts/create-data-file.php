<?php

/*
	Script for creating the data.json used in the demo.
 */

include('../../backend/vendor/autoload.php');

use IntruderAlert\Config;
use IntruderAlert\Ip;
use IntruderAlert\Lists;
use IntruderAlert\Report;
use IntruderAlert\Logger;

$eventsFilepath = '../events.json';
$dataFilepath = '../data.json';

$settings = [
	'features' => [
		'charts' => true,
		'updates' => false,
		'daemonLog' => false
	],
	'defaults' => [
		'chart' => 'last24hours',
		'pageSize' => 25,
	],
	'timezone' => 'Europe/London',
	'version' => ''
];

function createLists(string $path) {
	$file = file_get_contents($path);
	$events = json_decode($file, associative: true);

	$lists = new Lists();
	foreach ($events as $event) {
		$parts = explode(' ', $event['timestamp']);
	
		$dateNow = date('Y-m-d') . ' ' . $parts[1];
		$date = new DateTime($dateNow);
	
		if ($parts[0] !== '0') {
			$date->modify(sprintf('-%s day',  $parts[0]));
		}
	
		$timestamp = $date->format('Y-m-d H:i:s');
	
		// Is timestamp in the future
		if (strtotime($timestamp) > time()) {
			continue;
		}
	
		$ip = new Ip($event['ip']);
		$ip->setJail($event['jail']);
		$ip->setTimestamp($date->format('Y-m-d H:i:s'));
		$ip->setCountry($event['country']);
		$ip->setContinent($event['continent']);
		$ip->setNetwork($event['network']);
	
		$lists->addIp($ip->getDetails());
	}

	return $lists;
}

function createReport(Lists $lists, string $path, string $timezone) {
	$report = new Report(
		$lists->get(),
		$lists->getCounts(),
		$path,
		$timezone,
		new Logger()
	);

	$report->generate();
}

function addSettings(array $settings, string $path) {
	$file = file_get_contents($path);
	$data = json_decode($file, associative: true);

	$data['settings'] = $settings;

	$file = json_encode($data);
	file_put_contents($path, $file);
}

$lists = createLists($eventsFilepath);
createReport($lists, $dataFilepath, 'Europe/London');
addSettings($settings, $dataFilepath);
