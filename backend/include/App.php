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
		try {
			$this->processLogs();
			$this->generateReport();
		} catch (Exception $err) {
			$this->generateErrorReport($err->getMessage());
		}
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

	private function generateErrorReport(string $message): void
	{
		$data = [
			'error' => true,
			'message' => $message,
			'updated' => date('Y-m-d H:i:s')
		];

		file_put_contents(
			'../frontend/data.json', 
			json_encode($data)
		);
	}
}
