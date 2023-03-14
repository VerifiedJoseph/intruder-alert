<?php

class App
{
	private Lists $lists;

	public function __construct()
	{
		$this->lists = new Lists();
	}

	public function run()
	{
		$this->processLogs();
		$this->generateReport();
	}

	private function processLogs(): void
	{
		$sinceTimeStamp = '';

		$logs = new Logs(constant('LOG_FOLDER'));
		foreach ($logs->process() as $line) {
			if ($sinceTimeStamp != null) {
				$sinceTimeStamp = $line['timestamp'];
			}
		
			$ip = new Ip(
				$line['ip'],
				$line['jail'],
				$line['timestamp']
			);

			$this->lists->addIp($ip);
		}
	}

	private function generateReport(): void
	{
		$report = new Report($this->lists->get());
		$report->generate();
	}
}