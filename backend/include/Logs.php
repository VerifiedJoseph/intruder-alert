<?php

use Helper\File;
use Helper\Output;
use Exception\AppException;
use Exception\ReportException;

/**
 * Class for processing Fail2ban logs
 */
class Logs
{
    /** @var string $filenameRegex Log filename regex */
    private $filenameRegex = '/fail2ban\.log/';

    /** @var string $gzRegex Gzip file extension regex */
    private $gzRegex = '/.gz$/';

    /** @var string $lineRegex Log line regex */
    private $lineRegex = '/([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}),[0-9]+ fail2ban\.actions[\s]+\[[0-9]+]: [A-Z]+[\s]+\[([\w]+)] Ban ([0-9a-z.:]+)/';

    /** @var string $path Log folder path */
    private string $path = '';

    /**
     * 
     * @param string $path Log folder path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Process logs
     * 
     * @return array<int, array<string, string>>
     */
    public function process(): array
    {
        $rows = [];

        foreach($this->getFiles($this->path) as $file) {
            Output::text('Parsing ' . $file->getPathname());

            if (is_readable($file->getPathname()) === false) {
                throw new AppException('Failed to read file ' . $file->getPathname());
            }

            $contents = File::read($file->getPathname());
        
            if (preg_match($this->gzRegex, $file->getFilename())) {
                $contents = (string) gzdecode($contents);
            }

            $lines = explode("\n", $contents);
            $lineCount = count($lines);
            $banCount = 0;

            foreach ($lines as $line) {
                preg_match($this->lineRegex, $line, $match);

                if ($match != []) {
                    $banCount +=1;

                    $rows[] = [
                        'ip' => $match[3],
                        'jail' => $match[2],
                        'timestamp' => $this->formatTimestamp($match[1])
                    ];
                }
            }

            Output::text(
                'Scanned '. number_format($lineCount) .' lines and found ' .
                number_format($banCount) . ' bans in ' . $file->getPathname()
            );
        }

        if (count($rows) === 0) {
            throw new ReportException('No bans found');
        }

        Output::text('Found ' . number_format(count($rows)) . ' bans in all files.');

        return $rows;
    }

    /**
     * Get RegexIterator of logs file
     * 
     * @param string $path Log folder path
     */
    private function getFiles(string $path): RegexIterator
    {
        $directory = new RecursiveDirectoryIterator($path);
        $flattened = new RecursiveIteratorIterator($directory);

        return new RegexIterator($flattened, $this->filenameRegex);
    }

    /**
     * Format timestamp (convert from system to local time zone)
     * 
     * @param string $timestamp
     */
    private function formatTimestamp(string $timestamp): string
    {
        $date = new DateTime(
            $timestamp, 
            new DateTimeZone(Config::getSystemLogTimezone())
        );
    
        $date->setTimezone(new DateTimeZone(Config::getTimezone()));

        return $date->format('Y-m-d H:i:s');
    }
}
