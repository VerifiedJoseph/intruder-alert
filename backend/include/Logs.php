<?php

namespace IntruderAlert;

use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Timer;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Exception\ReportException;
use SplFileInfo;
use RegexIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use DateTime;
use DateTimeZone;

/**
 * Class for processing Fail2ban logs
 */
class Logs
{
    /** @var Config $config */
    private Config $config;

    /** @var string $filenameRegex Log filename regex */
    private $filenameRegex = '/fail2ban\.log/';

    /** @var string $gzRegex Gzip file extension regex */
    private $gzRegex = '/.gz$/';

    /** @var string $lineRegex Log line regex */
    private $lineRegex = <<<REGEX
     /(?<timestamp>[0-9]{4}-[0-9]{2}-[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2})
     ,[0-9]+\ fail2ban*.+
     \[(?<jail>[\w]+)]\ 
     Ban\ (?<ip>[0-9a-z.:]+)
     /ix
    REGEX;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Process logs
     *
     * @return array<int, array<string, string>>
     */
    public function process(): array
    {
        $rows = [];

        foreach ($this->getFiles() as $file) {
            $timer = new Timer();
            $timer->start();

            Output::text('Processing ' . $file->getPathname(), log: true);

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
                    $banCount += 1;

                    $rows[] = [
                        'ip' => $match[3],
                        'jail' => $match[2],
                        'timestamp' => $this->formatTimestamp($match[1])
                    ];
                }
            }

            $timer->stop();

            $message = sprintf(
                'Scanned %s lines and found %s bans in %s (%ss)',
                number_format($lineCount),
                number_format($banCount),
                $file->getPathname(),
                $timer->getTime()
            );

            Output::text($message, log: true);
        }

        if (count($rows) === 0) {
            throw new ReportException('No bans found');
        }

        $totalBans = number_format(count($rows));
        Output::text(sprintf('Found %s bans in all files.', $totalBans), log: true);

        return $rows;
    }

    /**
     * Get logs file
     *
     * @return RegexIterator|array<int, SplFileInfo>
     */
    private function getFiles(): RegexIterator|array
    {
        if ($this->config->getLogPaths() !== '') {
            $paths = array_unique(explode(',', $this->config->getLogPaths()));
            $files = [];

            foreach ($paths as $path) {
                $files[] = new SplFileInfo($path);
            }

            return $files;
        }

        $directory = new RecursiveDirectoryIterator($this->config->getLogFolder());
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
            new DateTimeZone($this->config->getSystemLogTimezone())
        );

        $date->setTimezone(new DateTimeZone($this->config->getTimezone()));

        return $date->format('Y-m-d H:i:s');
    }
}
