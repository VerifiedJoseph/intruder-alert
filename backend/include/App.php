<?php

use Helper\File;
use Helper\Json;
use Exception\ReportException;

class App
{
	/** @var Lists $lists */
	private Lists $lists;

	/** @var string $dataFilepath Report data filepath */
	private string $dataFilepath = './data/data.json';

	/** @var string $cacheFilepath Cache filepath */
	private string $cacheFilepath = './data/cache.json';

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
		if (File::exists($this->dataFilepath) === false) {
			return Json::encode([
				'error' => true,
				'message' => 'No data. Is the backend script setup?'
			]);
		}

		return File::read($this->dataFilepath);
	}

	/**
	 * Process logs
	 */
	private function processLogs(): void
	{
		$cache = new Cache($this->cacheFilepath);

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
			$this->dataFilepath,
			Json::encode($data)
		);
	}
}
