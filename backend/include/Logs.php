<?php

class Logs
{
	private $filenameRegex = '/fail2ban\.log/';
	private $gzRegex = '/.gz$/';
	private $lineRegex = '/([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}),[0-9]+ fail2ban\.(?:filter|actions)[\s]+\[[0-9]+]: [A-Z]+[\s]+\[([\w]+)] Ban ([0-9a-z.:]+|[0-9.]+)/';

	private $files;

	public function __construct(string $path)
	{
		$this->getFiles($path);
	}

	public function process()
	{
		$rows = [];

		foreach($this->files as $file) {
			echo $file->getPathname() . "\n";

			if (is_readable($file->getPathname()) === false) {
				throw new Exception('Backend error: Failed to read file ' . $file->getPathname());
			}

			$contents = file_get_contents($file->getPathname());
		
			if (preg_match($this->gzRegex, $file->getFilename())) {
				$contents = gzdecode($contents);
			}

			foreach (explode("\n", $contents) as $line) {
				preg_match($this->lineRegex, $line, $match);

				if ($match != []) {
					$rows[] = [
						'ip' => $match[3],
						'jail' => $match[2],
						'timestamp' => $match[1]
					];
				}
			}
		}

		if (count($rows) === 0) {
			throw new Exception('No ban events found');
		}

		return $rows;
	}

	private function getFiles(string $path)
	{
		$directory = new RecursiveDirectoryIterator($path);
		$flattened = new RecursiveIteratorIterator($directory);

		$this->files = new RegexIterator($flattened, $this->filenameRegex);
	}
}
