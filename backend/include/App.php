<?php

use Exception\AppException;
use Helper\File;
use Helper\Json;
use Exception\ReportException;

class App
{
	/** @var Lists $lists */
	private Lists $lists;

	public function __construct()
	{
		$this->lists = new Lists();
	}

	/**
	 * Run app
	 */
	public function run(): void
	{
		try {
			$this->processLogs();
			$this->generateReport();
		} catch (ReportException $err) {
			$this->generateErrorReport($err->getMessage());
		}
	}

	/**
	 * Get report JSON
	 */
	public function getJsonReport(): string
	{
		header('Content-type: application/json;');

		if (File::exists('./data/data.json') === false) {
			return Json::encode([
				'error' => true,
				'message' => 'No data. Is the backend script setup?'
			]);
		}

		return File::read('./data/data.json');
	}

	/**
	 * Process logs
	 */
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

	/**
	 * Generate report
	 */
	private function generateReport(): void
	{
		$report = new Report($this->lists->get());
		$report->generate();
	}

	/**
	 * Generate error report
	 * 
	 * @param string $message Error message
	 */
	private function generateErrorReport(string $message): void
	{
		$data = [
			'error' => true,
			'message' => $message,
			'updated' => date('Y-m-d H:i:s')
		];

		File::write(
			'./data/data.json',
			Json::encode($data)
		);
	}
}
