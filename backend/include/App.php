<?php

use Helper\File;
use Helper\Json;

class App
{
	private Lists $lists;

	public function __construct()
	{
		$this->lists = new Lists();
	}

	public function run()
	{
		try {
			$this->processLogs();
			$this->generateReport();
		} catch (Exception $err) {
			$this->generateErrorReport($err->getMessage());
		}
	}

	private function processLogs(): void
	{
		$cache = new Cache('./data/cache.json');

		$logs = new Logs(constant('LOG_FOLDER'));
		foreach ($logs->process() as $line) {

			if ($cache->hasItem($line['ip']) === true) {
				$cacheData = $cache->getItem($line['ip']);
				$ip = [
					'address' => $line['ip'],
					'jail' => $line['jail'],
					'timestamp' => $line['timestamp'],
					'network' => $cacheData['network'],
					'country' => $cacheData['country'],
				];
				$this->lists->addIp($ip);
			
			} else {
				$ip = new Ip(
					$line['ip'],
					$line['jail'],
					$line['timestamp']
				);

				$cache->addItem($ip->getDetails());
				$this->lists->addIp($ip->getDetails());
			}
		}

		$cache->save();
	}

	private function generateReport(): void
	{
		$report = new Report($this->lists->get());
		$report->generate();
	}

	private function generateErrorReport(string $message): void
	{
		$data = [
			'error' => true,
			'message' => $message,
			'updated' => date('Y-m-d H:i:s')
		];

		File::write(
			'../frontend/data.json',
			Json::encode($data)
		);
	}
}
